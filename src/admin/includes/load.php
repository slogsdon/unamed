<?php

require_once '../'. VENDOR_DIR . 'autoload.php';
include_once '../'. INCLUDES_DIR . 'orm.php';

foreach (glob('../' . MODELS_DIR . '*.php') as $filename) {
    include_once $filename;
}

foreach (glob('../' . INTERFACES_DIR . '*.php') as $filename) {
    include_once $filename;
}

foreach (glob('../' . CLASSES_DIR . '*.php') as $filename) {
    include_once $filename;
}

include_once INCLUDES_DIR . 'functions.php';
include_once INCLUDES_DIR . 'controller.php';

$un = new Unamed\Unamed(true);

$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/', 'Admin_Overview');
$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/posts/[*:action]?/[i:id]?', 'Admin_Posts');
$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/plugins/[*:action]?/[i:id]?', 'Admin_Plugins');
$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/themes/[*:action]?/[i:id]?', 'Admin_Themes');
$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/users/[*:action]?/[i:id]?', 'Admin_Users');
$un->addRoute('/' . trim(ADMIN_DIR, DS) . '/settings/[*:action]?/[i:id]?', 'Admin_Settings');

wp_register_style( $handle, $src, $deps, $ver, $media )
<link href="<?php admin_assets_url();?>css/lava.css" rel="stylesheet" type="text/css" />
<link href="<?php admin_assets_url();?>css/admin.css" rel="stylesheet" type="text/css" />
wp_register_script( $handle, $src, $deps, $ver, $in_footer )
<script src="<?php admin_assets_url();?>js/jquery.min.js" type="text/javascript"></script>
<script src="<?php admin_assets_url();?>js/admin.js" type="text/javascript"></script>

$un->run();
