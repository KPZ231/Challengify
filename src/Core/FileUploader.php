<?php

namespace Kpzsproductions\Challengify\Core;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Secure file upload handler class
 */
class FileUploader
{
    private $uploadDir;
    private $allowedTypes = [];
    private $maxFileSize = 0;
    private $errors = [];
    private $lastUploadedPath = null;

    /**
     * Constructor
     * 
     * @param string $uploadDir The upload directory path
     * @param array $allowedTypes Array of allowed MIME types
     * @param int $maxFileSize Maximum file size in bytes
     */
    public function __construct($uploadDir, $allowedTypes = [], $maxFileSize = 0)
    {
        // Ensure upload directory path is properly sanitized
        $this->uploadDir = rtrim(realpath($uploadDir) ?: $uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->allowedTypes = $allowedTypes;
        $this->maxFileSize = $maxFileSize;
        
        // Create upload directory if it doesn't exist
        if (!is_dir($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0755, true)) {
                $this->errors[] = "Failed to create upload directory: {$this->uploadDir}";
                Logger::error("Failed to create upload directory", ['dir' => $this->uploadDir]);
            } else {
                Logger::info("Created upload directory", ['dir' => $this->uploadDir]);
            }
        }
        
        // Verify directory is writable
        if (!is_writable($this->uploadDir)) {
            $this->errors[] = "Upload directory is not writable: {$this->uploadDir}";
            Logger::error("Upload directory not writable", ['dir' => $this->uploadDir]);
        }
    }

    /**
     * Upload a file with enhanced security checks
     * 
     * @param UploadedFile $file The uploaded file
     * @param string $customFilename Optional custom filename base (without extension)
     * @return string|false The filename of the uploaded file or false on failure
     */
    public function upload(UploadedFile $file, $customFilename = null)
    {
        $this->errors = [];
        $this->lastUploadedPath = null;
        
        // Check if file is valid
        if (!$file->isValid()) {
            $errorCode = $file->getError();
            $errorMessage = $this->getUploadErrorMessage($errorCode);
            $this->errors[] = "Upload error: {$errorMessage}";
            Logger::error("Invalid upload file", [
                'error_code' => $errorCode,
                'error_message' => $errorMessage,
                'file_name' => $file->getClientOriginalName()
            ]);
            return false;
        }
        
        // Use Security class to validate file
        $validationErrors = Security::validateFile($file, $this->allowedTypes, $this->maxFileSize);
        if (!empty($validationErrors)) {
            $this->errors = array_merge($this->errors, $validationErrors);
            Logger::error("File validation failed", [
                'errors' => $validationErrors,
                'file_name' => $file->getClientOriginalName()
            ]);
            return false;
        }
        
        // Generate a secure filename
        if ($customFilename) {
            // Sanitize custom filename
            $customFilename = Security::sanitizeFilename($customFilename);
            $filename = $customFilename . '.' . $file->getClientOriginalExtension();
        } else {
            // Generate a random filename
            $filename = Security::generateToken(16) . '.' . $file->getClientOriginalExtension();
        }
        
        // Ensure filename is unique
        $filename = $this->ensureUniqueFilename($filename);
        
        // Full path to save the file
        $fullPath = $this->uploadDir . $filename;
        
        try {
            // Move the file using move_uploaded_file for maximum security
            if (!$file->move($this->uploadDir, $filename)) {
                $this->errors[] = "Failed to move uploaded file";
                Logger::error("Failed to move file", [
                    'source' => $file->getPathname(),
                    'destination' => $fullPath,
                    'file_name' => $file->getClientOriginalName()
                ]);
                return false;
            }
            
            // Extra validation: check if file was successfully moved
            if (file_exists($fullPath)) {
                // Perform additional security scan on the uploaded file
                if (!$this->validateUploadedFile($fullPath, $file->getMimeType())) {
                    // If validation fails, delete the file and return false
                    @unlink($fullPath);
                    return false;
                }
                
                Logger::info("File uploaded successfully", [
                    'original_name' => $file->getClientOriginalName(),
                    'new_name' => $filename,
                    'path' => $fullPath
                ]);
                
                $this->lastUploadedPath = $fullPath;
                return $filename;
            } else {
                $this->errors[] = "Failed to move uploaded file";
                Logger::error("File move failed - file not found after move", [
                    'destination' => $fullPath,
                    'original_name' => $file->getClientOriginalName()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            $this->errors[] = "Exception during file upload: " . $e->getMessage();
            Logger::error("Exception during file upload", [
                'exception' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'destination' => $fullPath
            ]);
            return false;
        }
    }
    
    /**
     * Ensure a filename is unique in the upload directory
     */
    private function ensureUniqueFilename($filename)
    {
        $pathInfo = pathinfo($filename);
        $basename = $pathInfo['filename'];
        $extension = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';
        
        $counter = 1;
        $newFilename = $filename;
        
        while (file_exists($this->uploadDir . $newFilename)) {
            $newFilename = $basename . '_' . $counter . $extension;
            $counter++;
        }
        
        return $newFilename;
    }
    
    /**
     * Additional validation for uploaded files
     */
    private function validateUploadedFile($filePath, $expectedMimeType)
    {
        // Check if file exists
        if (!file_exists($filePath)) {
            $this->errors[] = "Uploaded file not found";
            return false;
        }
        
        // Verify file size again
        if ($this->maxFileSize > 0 && filesize($filePath) > $this->maxFileSize) {
            $this->errors[] = "File size exceeds maximum allowed size";
            return false;
        }
        
        // Verify mime type using finfo
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $actualMimeType = $finfo->file($filePath);
        
        if ($actualMimeType !== $expectedMimeType) {
            $this->errors[] = "File type mismatch: expected {$expectedMimeType}, got {$actualMimeType}";
            Logger::error("File type mismatch", [
                'expected' => $expectedMimeType,
                'actual' => $actualMimeType,
                'file_path' => $filePath
            ]);
            return false;
        }
        
        // Check for PHP code in image files (basic)
        if (strpos($expectedMimeType, 'image/') === 0) {
            $content = file_get_contents($filePath);
            if (preg_match('/<\\?php|eval\\(|base64_decode/i', $content)) {
                $this->errors[] = "Potentially malicious content detected in image file";
                Logger::error("Malicious content in image", ['file_path' => $filePath]);
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get error messages
     * 
     * @return array Array of error messages
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Get the last error message
     * 
     * @return string|null The last error message or null if no errors
     */
    public function getLastError()
    {
        return !empty($this->errors) ? end($this->errors) : null;
    }
    
    /**
     * Get the last uploaded file path
     * 
     * @return string|null The last uploaded file path or null if no file was uploaded
     */
    public function getLastUploadedPath()
    {
        return $this->lastUploadedPath;
    }
    
    /**
     * Get human-readable upload error message
     * 
     * @param int $errorCode The error code from UploadedFile::getError()
     * @return string Human-readable error message
     */
    private function getUploadErrorMessage($errorCode)
    {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
        ];
        
        return isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : 'Unknown upload error';
    }
} 