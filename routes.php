<?php
require_once __DIR__ . '/autoload.php';

use App\Controller\Auth;

$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case '/register':
       if( $method == 'POST' ) {
           $auth = new Auth();
           $auth->register();
       }else {
           require __DIR__ . '/view/auth/register.php';
       }
        break;
    case '/login' :
       if( $method == 'POST' ) {
           $auth = new Auth();
           $auth->login();
       }else {
           require __DIR__ . '/view/auth/login.php';
       }
        break;
    case '/event' :
    case '/event/index' :
        require __DIR__ . '/view/event/index.php';
        break;
    case '/event/login' :
        require __DIR__ . '/view/auth/login.php';
        break;
    case '/event/register' :
        require __DIR__ . '/view/auth/register.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/view/home.php';
        break;
}
