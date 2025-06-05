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

            // Validate required config (except password which can be empty for testing)
            foreach (['DB_HOST', 'DB_DATABASE', 'DB_USERNAME'] as $key) {
                if (empty($config[$key])) {
                    throw new \Exception("Database configuration error: missing $key");
                }
            }

            // For testing purposes, allow DB_PASSWORD to be null or empty
            if (!isset($config['DB_PASSWORD'])) {
                $config['DB_PASSWORD'] = '';
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
                ],
            ];

            try {
                self::$instance = new Medoo($databaseConfig);
            } catch (\PDOException $e) {
                // Do not leak sensitive info in production
                throw new \Exception('Database connection failed: ' . (getenv('APP_ENV') === 'development' ? $e->getMessage() : ''));
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
            $dotenv = Dotenv::createImmutable($rootPath);
            $dotenv->safeLoad();
            $envLoaded = true;
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

        // For testing: add hardcoded values if needed
        if (empty($vars)) {
            $vars = [
                'DB_HOST' => 'localhost',
                'DB_DATABASE' => 'challengify',
                'DB_USERNAME' => 'root',
                'DB_PASSWORD' => '',
                'DB_CHARSET' => 'utf8mb4',
                'DB_PORT' => '3306'
            ];
        }

        return $vars;
    }
}
