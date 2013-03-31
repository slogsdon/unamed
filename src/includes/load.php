<?php
/**
 * Unamed - a WordPress replacement
 *
 * Bootstraps the system by loading what needs to be loaded, initiating the
 * base class, and calling the run method to get everything going.
 * 
 * @category CMS
 * @package  Unamed
 * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
 * @license  MIT http://mit.edu/
 * @link     http://bitbucket.org/slogsdon/unamed
 */

require_once VENDOR_DIR . 'autoload.php';
require_once INCLUDES_DIR . 'orm.php';

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

require_once INCLUDES_DIR . 'functions.php';

$un->run();
