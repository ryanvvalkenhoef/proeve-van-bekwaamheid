<?php

spl_autoload_register(function ($class) {
    $path = "classes/";
    $extension = ".class.php";
    $fullPath = $path . $class . $extension;

    if (!file_exists($fullPath)) {
        return false;
    }

    include_once $fullPath;
});
?>