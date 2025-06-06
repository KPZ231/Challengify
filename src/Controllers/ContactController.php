<?php

namespace Kpzsproductions\Challengify\Controllers;

use Kpzsproductions\Challengify\Core\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller{

    public function index(){
        
        ob_start();
        require_once __DIR__ . '/../Views/contact.php';
        $content = ob_get_clean();

        return new Response($content);
    }

    public function submit(Request $request){
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialize errors array
        $errors = [];
        
        // Validate CSRF token
        $submittedToken = $request->request->get('csrf_token');
        if (!$this->validateCsrfToken($submittedToken)) {
            $errors['csrf'] = 'Invalid form submission';
            
            // If CSRF validation fails, redirect immediately for security
            $_SESSION['contact_errors'] = $errors;
            return $this->redirect('/contact');
        }
        
        // Get form data and sanitize
        $name = htmlspecialchars(trim($request->request->get('name')));
        $email = filter_var(trim($request->request->get('email')), FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars(trim($request->request->get('subject')));
        $message = htmlspecialchars(trim($request->request->get('message')));
        
        // Validate name
        if (empty($name) || strlen($name) < 2) {
            $errors['name'] = 'Please enter a valid name (at least 2 characters)';
        }
        
        // Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        
        // Validate subject
        if (empty($subject) || strlen($subject) < 3) {
            $errors['subject'] = 'Please enter a valid subject (at least 3 characters)';
        }
        
        // Validate message
        if (empty($message) || strlen($message) < 10) {
            $errors['message'] = 'Please enter a message (at least 10 characters)';
        }
        
        // Check honeypot field (anti-spam)
        if (!empty($request->request->get('website'))) {
            // This is likely a bot - silently redirect without processing
            return $this->redirectWithSuccess('/contact', 'Message sent successfully!');
        }
        
        // Handle reCAPTCHA validation here if implemented
        // $recaptcha = $request->request->get('g-recaptcha-response');
        // if (!$this->validateRecaptcha($recaptcha)) {
        //     $errors['recaptcha'] = 'Please verify you are not a robot';
        // }
        
        // Process if no errors
        if (empty($errors)) {
            // Here you would typically:
            // 1. Log the message
            // 2. Send an email notification
            // 3. Store in database
            
            // Example email sending (needs implementation)
            // $this->sendEmail($name, $email, $subject, $message);
            
            // Log contact attempt (optional)
            $this->logContactAttempt($request, true);
            
            // Set success message and redirect
            $_SESSION['contact_success'] = true;
            $_SESSION['contact_message'] = 'Thank you! Your message has been sent successfully.';
            
            // Generate a new CSRF token for the next form
            $this->regenerateCsrfToken();
            
            return $this->redirect('/contact');
        } else {
            // Log failed contact attempt (optional)
            $this->logContactAttempt($request, false, $errors);
            
            // Store errors and form data in session for redisplay
            $_SESSION['contact_errors'] = $errors;
            $_SESSION['contact_form_data'] = [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message
            ];
            
            return $this->redirect('/contact');
        }
    }
    
    /**
     * Helper method to validate CSRF token
     */
    private function validateCsrfToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Helper method to regenerate CSRF token
     */
    private function regenerateCsrfToken() {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Helper method to log contact attempts
     */
    private function logContactAttempt($request, $success, $errors = []) {
        // You can implement logging logic here
        // For example, log to file or database
        
        $ip = $request->getClientIp();
        $userAgent = $request->headers->get('User-Agent');
        $timestamp = date('Y-m-d H:i:s');
        
        // Example log message
        $logMessage = sprintf(
            "[%s] Contact form %s from IP: %s, User-Agent: %s", 
            $timestamp, 
            $success ? 'success' : 'failure', 
            $ip, 
            $userAgent
        );
        
        // Add to log file (ensure logs directory exists and is writable)
        if (is_dir(__DIR__ . '/../../logs') && is_writable(__DIR__ . '/../../logs')) {
            file_put_contents(
                __DIR__ . '/../../logs/contact_form.log', 
                $logMessage . PHP_EOL, 
                FILE_APPEND
            );
        }
    }
    
    /**
     * Helper method to redirect with a success message
     */
    private function redirectWithSuccess($url, $message) {
        $_SESSION['contact_success'] = true;
        $_SESSION['contact_message'] = $message;
        return $this->redirect($url);
    }
    
    /**
     * Helper method to redirect
     */
    protected function redirect(string $url): Response {
        header('Location: ' . $url);
        exit();
    }
    
    /**
     * Helper method to validate reCAPTCHA
     * Implement when adding reCAPTCHA
     */
    /*
    private function validateRecaptcha($recaptcha) {
        // Implementation depends on your reCAPTCHA setup
        return true;
    }
    */
    
    /**
     * Helper method to send email
     * Implement using PHPMailer or another solution
     */
    /*
    private function sendEmail($name, $email, $subject, $message) {
        // Implement email sending here using PHPMailer
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com';
            $mail->Password = 'your-password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            // Recipients
            $mail->setFrom('contact@challengify.com', 'Challengify Contact Form');
            $mail->addAddress('admin@challengify.com', 'Admin');
            $mail->addReplyTo($email, $name);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Contact Form: ' . $subject;
            $mail->Body = "
                <h2>Contact Form Submission</h2>
                <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                <p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
                <p><strong>Message:</strong></p>
                <p>" . nl2br(htmlspecialchars($message)) . "</p>
            ";
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log error
            error_log('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
            return false;
        }
    }
    */
}
 ?>
