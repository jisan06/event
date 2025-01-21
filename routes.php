<?php
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/register' :
        require __DIR__ . '/view/auth/register.php';
        break;
    case '/event/' :
    case '/event/index' :
        require __DIR__ . '/view/home.php';
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
