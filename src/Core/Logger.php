<?php

namespace Kpzsproductions\Challengify\Core;

/**
 * Simple logger class for the application
 */
class Logger
{
    private static $logFile = null;
    private static $enabled = true;

    /**
     * Initialize the logger with a specific log file
     */
    public static function init($logFile = null)
    {
        if ($logFile === null) {
            self::$logFile = __DIR__ . '/../../logs/app.log';
        } else {
            self::$logFile = $logFile;
        }
        
        // Create logs directory if it doesn't exist
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    /**
     * Enable or disable logging
     */
    public static function setEnabled($enabled)
    {
        self::$enabled = $enabled;
    }

    /**
     * Log an info message
     */
    public static function info($message, $context = [])
    {
        self::log('INFO', $message, $context);
    }

    /**
     * Log a debug message
     */
    public static function debug($message, $context = [])
    {
        self::log('DEBUG', $message, $context);
    }

    /**
     * Log an error message
     */
    public static function error($message, $context = [])
    {
        self::log('ERROR', $message, $context);
    }

    /**
     * Log a warning message
     */
    public static function warning($message, $context = [])
    {
        self::log('WARNING', $message, $context);
    }

    /**
     * Write a log entry
     */
    private static function log($level, $message, $context = [])
    {
        if (!self::$enabled) {
            return;
        }

        if (self::$logFile === null) {
            self::init();
        }

        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        $logEntry = "[$timestamp] [$level] $message$contextStr" . PHP_EOL;

        file_put_contents(self::$logFile, $logEntry, FILE_APPEND);
    }

    /**
     * Get the contents of the log file
     */
    public static function getLogContents($lines = null)
    {
        if (!file_exists(self::$logFile)) {
            return "Log file does not exist.";
        }

        if ($lines === null) {
            return file_get_contents(self::$logFile);
        }

        // Get the last N lines
        $file = new \SplFileObject(self::$logFile, 'r');
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();

        $startLine = max(0, $totalLines - $lines);
        $lineCount = 0;
        $result = [];

        $file->rewind();
        while (!$file->eof()) {
            $line = $file->fgets();
            if ($lineCount >= $startLine) {
                $result[] = $line;
            }
            $lineCount++;
        }

        return implode('', $result);
    }

    /**
     * Clear the log file
     */
    public static function clearLog()
    {
        if (self::$logFile !== null && file_exists(self::$logFile)) {
            file_put_contents(self::$logFile, '');
        }
    }
} 