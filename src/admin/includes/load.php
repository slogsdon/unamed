<?php

require_once '../'. VENDOR_DIR . 'autoload.php';
include_once '../'. INCLUDES_DIR . 'orm.php';
include_once INCLUDES_DIR . 'functions.php';
include_once INCLUDES_DIR . 'controller.php';

$un = new Unamed\Unamed(true);

$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/?', 'Admin\Overview');
$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/posts/[*:action]?/[i:id]?', 'Admin\Posts');
$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/plugins/[*:action]?/[i:id]?', 'Admin\Plugins');
$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/themes/[*:action]?/[i:id]?', 'Admin\Themes');
$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/users/[*:action]?/[i:id]?', 'Admin\Users');
$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/settings/[*:action]?/[i:id]?', 'Admin\Settings');

$un->run();
