<?php

spl_autoload_register(function ($class) {
    // Replace namespace backslashes with directory separators
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Define the base directory for all classes in the "App" namespace
    $baseDir = __DIR__ . '/app/';

    // Check if the class is part of the "App" namespace
    if (strpos($class, 'App\\') === 0) {
        $file = $baseDir . substr($class, 4) . '.php';  // Remove 'App\' from the namespace

        // Check if the file exists and require it
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    // If the class isn't found, show an error
    die("The file for class {$class} could not be found.");
});
