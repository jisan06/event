<?php
namespace App;

class Helper
{
    public static function view($view)
    {
        // Specify the path for views
        $viewPath = __DIR__ . "/../view/{$view}";

        if (file_exists($viewPath)) {
            return $viewPath; // Include the view file
        } else {
            die("View not found: {$viewPath}");
        }
    }
}
