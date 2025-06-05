<?php

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use Kpzsproductions\Challengify\Controllers\HomeController;
use Kpzsproductions\Challengify\Controllers\AuthController;
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
});

// Parse the URL path
$routeInfo = $dispatcher->dispatch(
    $request->getMethod(),
    $request->getPathInfo()
);

// Handle the route
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        $response = new Response('404 Not Found', 404);
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
        list($class, $method) = explode('::', $handler, 2);
        $class = "Kpzsproductions\\Challengify\\$class";
        $controller = new $class();
        $response = $controller->$method($request, $vars);
        
        // If the response is not an instance of Response, create one
        if (!$response instanceof Response) {
            $response = new Response($response);
        }
        break;
}

// Send the response
$response->send();
