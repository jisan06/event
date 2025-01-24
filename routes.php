<?php
require_once __DIR__ . '/autoload.php';

use App\Controller\Auth;
use App\Controller\Event;

$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$request = trim(parse_url($request, PHP_URL_PATH), '/');
$routeSegments = explode('/', $request); // Split the URI into segments

if ($routeSegments[0] === 'events') {
    $event = new Event();

    switch (true) {
        case $request === 'events' && $method === 'GET':
            // List all events
            $event->index();
            break;

        case $request === 'events/create' && $method === 'GET':
            // Show the event creation form
            $event->create();
            break;

        case $request === 'events' && $method === 'POST':
            // Store a new event
            $event->store();
            break;

        case isset($routeSegments[1]) && is_numeric($routeSegments[1]) && $method === 'GET':
            // Show specific event for editing
            $_GET['id'] = $routeSegments[1];
            $event->edit();
            break;

        case isset($routeSegments[1]) &&
        is_numeric($routeSegments[1]) &&
        (isset($_POST['_method']) && $_POST['_method'] === 'PUT'):
            // Update a specific event
            $event->update($routeSegments[1]);
            break;

        case isset($routeSegments[1]) &&
        is_numeric($routeSegments[1]) &&
        (isset($_POST['_method']) && $_POST['_method'] === 'DELETE'):
            // Delete a specific event
            $event->delete($routeSegments[1]);
            break;

        default:
            http_response_code(404);
            echo "Event route not found.";
            break;
    }
} else {
    switch ($routeSegments[0]) {
        case 'register':
            $auth = new Auth();
            if ($method === 'POST') {
                $auth->register();
            } else {
                require __DIR__ . '/view/auth/register.php';
            }
            break;

        case 'login':
        case '':
            $auth = new Auth();
            if ($method === 'POST') {
                $auth->login();
            } else {
                require __DIR__ . '/view/auth/login.php';
            }
            break;

        case 'logout':
            $auth = new Auth();
            $auth->logout();
            break;

        default:
            http_response_code(404);
            echo "Route not found.";
            break;
    }
}
