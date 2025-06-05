<?php

namespace Kpzsproductions\Challengify\Controllers;

use Kpzsproductions\Challengify\Core\Controller;
use Kpzsproductions\Challengify\Core\Database;

class UserDashboard extends Controller{

    public function index(){
        require_once __DIR__ . '/../Views/user/user-dashboard.php';
    }
}
