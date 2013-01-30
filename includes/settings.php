<?php

// User-configurable
define('DB_HOST', 'localhost');
define('DB_NAME', 'shanelogsdon_com');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_LOGGING', true);

// Stop Editing Here
define('BASE_DIR', './');
define('INCLUDES_DIR', BASE_DIR . 'includes/');
define('MODELS_DIR', BASE_DIR . 'models/');
define('THEMES_DIR', BASE_DIR . 'themes/');
define('VENDOR_DIR', BASE_DIR . 'vendor/');

session_start();

require_once VENDOR_DIR . 'autoload.php';
include_once INCLUDES_DIR . 'orm.php';	
include_once INCLUDES_DIR . 'baseClasses.php';

$un = new Unamed();

include_once INCLUDES_DIR . 'functions.php';