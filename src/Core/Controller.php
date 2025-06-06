<?php

namespace Kpzsproductions\Challengify\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    /**
     * @var Request
     */
    protected $request;
    
    /**
     * @var array
     */
    protected $data = [];
    
    /**
     * @var string
     */
    protected $viewPath;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->viewPath = __DIR__ . '/../Views/';
    }
    
    /**
     * Get request object
     * 
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
    
    /**
     * Set data for view
     * 
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setData(string $key, $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }
    
    /**
     * Get data
     * 
     * @param string|null $key
     * @return mixed
     */
    public function getData(?string $key = null)
    {
        if ($key === null) {
            return $this->data;
        }
        
        return $this->data[$key] ?? null;
    }
    
    /**
     * Render view
     * 
     * @param string $view
     * @param array $data
     * @return Response
     */
    protected function render(string $view, array $data = []): Response
    {
        $this->data = array_merge($this->data, $data);
        extract($this->data);
        
        ob_start();
        include $this->viewPath . $view . '.php';
        $content = ob_get_clean();
        
        $response = new Response($content);
        return $this->addSecurityHeaders($response);
    }
    
    /**
     * Redirect to url
     * 
     * @param string $url
     * @return Response
     */
    protected function redirect(string $url): Response
    {
        $response = new Response();
        $response->headers->set('Location', $url);
        $response->setStatusCode(Response::HTTP_FOUND);
        
        return $this->addSecurityHeaders($response);
    }
    
    /**
     * JSON response
     * 
     * @param mixed $data
     * @param int $status
     * @return Response
     */
    protected function json($data, int $status = 200): Response
    {
        $response = new Response(json_encode($data), $status);
        $response->headers->set('Content-Type', 'application/json');
        
        return $this->addSecurityHeaders($response);
    }
    
    /**
     * Add security headers to response
     * 
     * @param Response $response
     * @return Response
     */
    protected function addSecurityHeaders(Response $response): Response
    {
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");
        
        return $response;
    }
}