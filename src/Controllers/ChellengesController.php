<?php
namespace Kpzsproductions\Challengify\Controllers;

use Kpzsproductions\Challengify\Core\Controller;
use Symfony\Component\HttpFoundation\Response;
class ChellengesController extends Controller{
    public function index(){
         // Przechwycenie zawartości widoku zamiast bezpośredniego wysłania
         ob_start();
         require __DIR__ . '/../Views/chellenges.php';
         $content = ob_get_clean();
         
         // Zwrócenie zawartości jako Response
         return new Response($content);
    }
}
?>