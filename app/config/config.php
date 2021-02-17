<?php
// Define constants needed for the application

// APPROOT
define('APPROOT', dirname(dirname(__FILE__)));

// URLROOT (Dynamic links)
$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
define('URLROOT', $root);

// Sitename
define('SITENAME', 'Event manager');