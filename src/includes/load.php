<?php

require_once VENDOR_DIR . 'autoload.php';
include_once INCLUDES_DIR . 'orm.php';

foreach (glob(INTERFACES_DIR . '*.php') as $filename) {
    include_once $filename;
}

foreach (glob(CLASSES_DIR . '*.php') as $filename) {
    include_once $filename;
}

foreach (glob(CONTROLLERS_DIR . '*.php') as $filename) {
    include_once $filename;
}

$un = new Unamed\Unamed();

include_once INCLUDES_DIR . 'functions.php';

$un->run();
