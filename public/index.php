<?php

require_once __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Respect\Validation\Validator as v;
use Whoops\Run as WhoopsRun;
use Whoops\Handler\PrettyPageHandler;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Error handling
$whoops = new WhoopsRun;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

// Create Request from globals
$request = Request::createFromGlobals();

// Set up CSRF protection
if (!isset($_SESSION)) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'cookie_samesite' => 'Lax'
    ]);
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Before setting up routes, require the controller files
require_once __DIR__ . '/../src/Controllers/HomeController.php';
require_once __DIR__ . '/../src/Controllers/AuthController.php';

// Setup routes with FastRoute
$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    // Home route
    $r->addRoute('GET', '/', [HomeController::class, 'index']);
    
    // Auth routes
    $r->addRoute('GET', '/register', [AuthController::class, 'showRegister']);
    $r->addRoute('POST', '/register', [AuthController::class, 'register']);
    $r->addRoute('GET', '/login', [AuthController::class, 'showLogin']);
    $r->addRoute('POST', '/login', [AuthController::class, 'login']);
    
    // Email verification
    $r->addRoute('GET', '/verify-email', [AuthController::class, 'verifyEmail']);
    
    // Password reset
    $r->addRoute('GET', '/forgot-password', [AuthController::class, 'showForgotPassword']);
    $r->addRoute('POST', '/forgot-password', [AuthController::class, 'forgotPassword']);
    $r->addRoute('GET', '/reset-password', [AuthController::class, 'showResetPassword']);
    $r->addRoute('POST', '/reset-password', [AuthController::class, 'resetPassword']);
});

// Fetch method and URI from Request
$httpMethod = $request->getMethod();
$uri = $request->getPathInfo();

// Strip query string and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// Dispatch the request
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // 404 Not Found
        $response = new Response('Not Found', 404);
        break;
        
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // 405 Method Not Allowed
        $allowedMethods = $routeInfo[1];
        $response = new Response('Method Not Allowed', 405);
        $response->headers->set('Allow', implode(', ', $allowedMethods));
        break;
        
    case FastRoute\Dispatcher::FOUND:
        // Route found - call controller
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        
        // Create the controller instance
        $controllerName = $handler[0];
        $methodName = $handler[1];
        
        // Instantiate controller
        $controller = new $controllerName();
        
        // Security validation - example for POST requests
        if ($httpMethod === 'POST') {
            // CSRF protection
            $submittedToken = $request->request->get('csrf_token');
            if (!$submittedToken || !hash_equals($_SESSION['csrf_token'], $submittedToken)) {
                $response = new Response('Invalid CSRF token', 403);
                break;
            }
            
            // Add additional input validation with Respect\Validation here
            // Example:
            // if (!v::key('email', v::email())->validate($request->request->all())) {
            //     $response = new Response('Invalid input', 400);
            //     break;
            // }
        }
        
        // Call the method
        ob_start();
        $controller->$methodName($vars);
        $content = ob_get_clean();
        
        $response = new Response($content);
        break;
}

// Send the response
$response->send();
