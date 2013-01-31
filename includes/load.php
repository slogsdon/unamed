<?php

require_once VENDOR_DIR . 'autoload.php';
include_once INCLUDES_DIR . 'orm.php';
include_once INCLUDES_DIR . 'base_classes.php';

$un = new Unamed();

include_once INCLUDES_DIR . 'functions.php';

$un->run();
