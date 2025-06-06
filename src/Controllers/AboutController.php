<?php

namespace Kpzsproductions\Challengify\Controllers;

use Kpzsproductions\Challengify\Core\Controller;
use Symfony\Component\HttpFoundation\Response;


class AboutController extends Controller{

    public function index(){
        
        ob_start();
        require_once __DIR__ . '/../Views/about.php';
        $content = ob_get_clean();

        return new Response($content);

    }
}
?>