<?php

require_once VENDOR_DIR . 'autoload.php';
include_once INCLUDES_DIR . 'orm.php';

foreach (glob(CLASSES_DIR . '*.php') as $filename)
{
    include_once $filename;
}

foreach (glob(HANDLERS_DIR . '*.php') as $filename)
{
    include_once $filename;
}

$un = new Unamed();

include_once INCLUDES_DIR . 'functions.php';

$un->run();
