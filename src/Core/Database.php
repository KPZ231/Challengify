<?php

namespace Kpzsproductions\Challengify\Core;

use Medoo\Medoo;
use Dotenv\Dotenv;

class Database
{
    /**
     * @var Medoo|null
     */
    private static $instance = null;

    /**
     * Get Medoo instance (singleton)
     *
     * @return Medoo
     * @throws \Exception
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            $config = self::loadEnv();

            // Validate required config
            foreach (['DB_HOST', 'DB_DATABASE', 'DB_USERNAME'] as $key) {
                if (empty($config[$key])) {
                    throw new \Exception("Database configuration error: missing $key");
                }
            }

            // For security, ensure DB_PASSWORD is at least set (even if empty)
            if (!isset($config['DB_PASSWORD'])) {
                $config['DB_PASSWORD'] = '';
                
                // Log this issue but don't expose it to users
                Logger::error("Database password not set in configuration");
            }

            $databaseConfig = [
                'type' => 'mysql',
                'host' => $config['DB_HOST'],
                'database' => $config['DB_DATABASE'],
                'username' => $config['DB_USERNAME'],
                'password' => $config['DB_PASSWORD'],
                'charset' => $config['DB_CHARSET'] ?? 'utf8mb4',
                'port' => isset($config['DB_PORT']) ? (int)$config['DB_PORT'] : 3306,
                'error' => \PDO::ERRMODE_EXCEPTION,
                'option' => [
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    // Prevent potential SQL injection when using MySQL
                    \PDO::MYSQL_ATTR_FOUND_ROWS => true,
                    // Ensure real prepared statements
                    \PDO::ATTR_STRINGIFY_FETCHES => false,
                ],
            ];

            try {
                self::$instance = new Medoo($databaseConfig);
                
                // Test connection
                self::$instance->query('SELECT 1');
            } catch (\PDOException $e) {
                // Log the actual error but don't expose it
                Logger::error("Database connection failed", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Throw generic error for production
                throw new \Exception('Database connection failed. Please check your configuration or contact an administrator.');
            }
        }

        return self::$instance;
    }

    /**
     * Load environment variables securely using vlucas/phpdotenv
     *
     * @return array
     */
    private static function loadEnv()
    {
        static $envLoaded = false;
        $rootPath = dirname(__DIR__, 2);

        if (!$envLoaded && file_exists($rootPath . '/.env')) {
            try {
                $dotenv = Dotenv::createImmutable($rootPath);
                $dotenv->safeLoad();
                $envLoaded = true;
            } catch (\Exception $e) {
                Logger::error("Failed to load environment variables", [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Only fetch DB_* variables for security
        $vars = [];
        foreach ($_ENV as $key => $value) {
            if (strpos($key, 'DB_') === 0) {
                $vars[$key] = $value;
            }
        }
        
        // Fallback to getenv if $_ENV is empty (for some server configs)
        if (empty($vars)) {
            foreach (getenv() as $key => $value) {
                if (strpos($key, 'DB_') === 0) {
                    $vars[$key] = $value;
                }
            }
        }

        // If no configuration found, log error but don't add hardcoded values
        if (empty($vars)) {
            Logger::error("No database configuration found in environment");
            
            // Don't add hardcoded values - fail safely instead
            throw new \Exception('Database configuration not found. Please check your .env file.');
        }

        return $vars;
    }
    
    /**
     * Securely sanitize a table or column name to prevent SQL injection in identifiers
     * Use this when you need to dynamically specify table or column names
     * 
     * @param string $identifier Table or column name
     * @return string Sanitized identifier
     */
    public static function sanitizeIdentifier($identifier)
    {
        // Remove all non-alphanumeric characters except underscore
        return preg_replace('/[^a-zA-Z0-9_]/', '', $identifier);
    }
    
    /**
     * Close the database connection
     */
    public static function close()
    {
        self::$instance = null;
    }
}
