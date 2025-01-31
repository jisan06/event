<?php
require_once __DIR__ . '/autoload.php';

use App\Controller\Auth;
use App\Controller\Event;

session_start();
$is_logged_in = App\Helper::is_logged_in();

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$scriptDir = rtrim($scriptDir, '/');
$base_url = $protocol . '://' . $host . $scriptDir . '/';

define('BASE_URL', $base_url);

$method = $_SERVER['REQUEST_METHOD'];
// Extract only the path from the full request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = trim($requestUri, '/'); // Remove the trailing slash if exists

// Get base directory from the script name
$baseDir = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'))[0];

// If the request URI starts with the base directory, remove it
if (strpos($requestUri, $baseDir) === 0) {
    $requestUri = substr($requestUri, strlen($baseDir) + 1);
}

$routeSegments = explode('/', $requestUri); // Split the path into segments
$request = implode('/', $routeSegments); // Rebuild the request URI

// Route Handling
if ($routeSegments[0] === 'events') {
    if(!$is_logged_in) {
        header("Location: " . BASE_URL);
    }
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

        case isset($routeSegments[2]) &&
            is_numeric($routeSegments[2]) &&
            $request === 'events/register/' . $routeSegments[2]:

            // View event register page
            if($method === 'GET') {
                $event->register($routeSegments[2]);
            } elseif($method === 'POST') {
                $event->register_event($routeSegments[2]);
            }
            break;

        case isset($routeSegments[2]) &&
            is_numeric($routeSegments[2]) &&
            $request === 'events/csv/' . $routeSegments[2] &&
            $method === 'GET' :

            // Download csv file
            $event->download($routeSegments[2]);
            break;

        default:
            http_response_code(404);
            echo "Event route not found.";
            break;
    }
} else {
    switch ($routeSegments[0]) {
        case '':
            require_once App\Helper::view('index.php');
            break;
        case 'register':
            if($is_logged_in) {
                header("Location: " . BASE_URL);
            }else {
                $auth = new Auth();
                if ($method === 'POST') {
                    $auth->register();
                } else {
                    require __DIR__ . '/view/auth/register.php';
                }
            }
            break;

        case 'login':
            if($is_logged_in) {
                header("Location: " . BASE_URL);
            }else {
                $auth = new Auth();
                if ($method === 'POST') {
                    $auth->login();
                } else {
                    require \App\Helper::view('auth/login.php');
                }
            }
            break;

        case 'logout':
            if(!$is_logged_in) {
                header("Location: " . BASE_URL);
            }else {
                $auth = new Auth();
                $auth->logout();
            }
            break;

        default:
            http_response_code(404);
            require \App\Helper::view('404.php');
            break;
    }
}
