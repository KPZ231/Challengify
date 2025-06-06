<?php

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use Kpzsproductions\Challengify\Controllers\HomeController;
use Kpzsproductions\Challengify\Controllers\AuthController;
use Kpzsproductions\Challengify\Controllers\AdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Error handling
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

// Create request object 
$request = Request::createFromGlobals();

// Initialize router
$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    // Define routes   
    // Home page
    $r->addRoute('GET', '/', 'Controllers\\HomeController::index');
    
    // Authentication routes
    $r->addRoute('GET', '/login', 'Controllers\\AuthController::login');
    $r->addRoute('POST', '/login', 'Controllers\\AuthController::processLogin');
    $r->addRoute('GET', '/register', 'Controllers\\AuthController::register');
    $r->addRoute('POST', '/register', 'Controllers\\AuthController::processRegister');
    $r->addRoute('GET', '/logout', 'Controllers\\AuthController::logout');
    
    // User dashboard
    $r->addRoute('GET', '/dashboard', 'Controllers\\UserDashboard::index');
    
    // User settings
    $r->addRoute('GET', '/settings', 'Controllers\\UserDashboard::settings');
    $r->addRoute('POST', '/update-settings', 'Controllers\\UserDashboard::updateSettings');

    // Challenges
    $r->addRoute('GET', '/challenges', 'Controllers\\ChellengesController::index');
    
    // Admin routes
    $r->addRoute('GET', '/admin', 'Controllers\\AdminController::index');
    $r->addRoute('GET', '/admin/logs', 'Controllers\\AdminController::viewLogs');
    $r->addRoute('GET', '/admin/logs/clear', 'Controllers\\AdminController::clearLogs');
    
    // Debug routes
    $r->addRoute('GET', '/check_session', function() {
        require __DIR__ . '/check_session.php';
        return '';
    });
    
    $r->addRoute('GET', '/fix_admin_role', function() {
        require __DIR__ . '/fix_admin_role.php';
        return '';
    });

    // About
    $r->addRoute('GET', '/about', 'Controllers\\AboutController::index');

    // Contact
    $r->addRoute('GET', '/contact', 'Controllers\\ContactController::index');
});

// Parse the URL path
$routeInfo = $dispatcher->dispatch(
    $request->getMethod(),
    $request->getPathInfo()
);

// Handle the route
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        $response = new Response('', 404);
        require_once __DIR__ . '/../src/Views/notfound.html';
        break;
        
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        $response = new Response('405 Method Not Allowed', 405);
        $response->headers->set('Allow', implode(', ', $allowedMethods));
        require_once __DIR__ . '/../src/Views/notallowed.html';
        break;
        
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        
        // Call the handler
        if (is_callable($handler)) {
            // Handle anonymous functions
            $response = $handler($request, $vars);
        } else {
            // Handle controller methods
            list($class, $method) = explode('::', $handler, 2);
            $class = "Kpzsproductions\\Challengify\\$class";
            $controller = new $class();
            $response = $controller->$method($request, $vars);
        }
        
        // If the response is not an instance of Response, create one
        if (!$response instanceof Response) {
            $response = new Response($response);
        }
        break;
}

// Send the response
$response->send();
