<?php
// Define constants needed for the application

// APPROOT
define('APPROOT', dirname(dirname(__FILE__)));

// URLROOT (Dynamic links)
$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/proeve-van-bekwaamheid';
define('URLROOT', $root);

// NUMBER FOR RESULTS PER PAGE IN PANELS
define('PAGE_RESULTS_NUM', 5);

// Sitename
define('SITENAME', 'Proeve van Bekwaamheid');