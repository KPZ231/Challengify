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
        
        return new Response($content);
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
        
        return $response;
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
        
        return $response;
    }
}