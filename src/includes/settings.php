<?php
/**
 * Unamed - a WordPress replacement
 *
 * Contains basic user-configurable settings
 *
 * @category CMS
 * @package  Unamed
 * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
 * @license  MIT http://mit.edu/
 * @link     http://bitbucket.org/slogsdon/unamed
 */

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'shanelogsdon_com');
define('DB_USER', 'root');
define('DB_PASS', '$emeleted46');
define('DB_LOGGING', true);

define('ENABLE_CACHE', true);
define('ENABLE_PAGE_CACHE', false);

// Directories
define('DS', '/');
define('BASE_DIR', rtrim($_SERVER['DOCUMENT_ROOT'], '/') . DS);
define('INCLUDES_DIR', 'includes' . DS);

define('ADMIN_DIR', 'admin' . DS);
define('CACHE_DIR', INCLUDES_DIR . 'cache' . DS);
define('CLASSES_DIR', INCLUDES_DIR . 'classes' . DS);
define('CONTROLLERS_DIR', INCLUDES_DIR . 'controllers' . DS);
define('INTERFACES_DIR', INCLUDES_DIR . 'interfaces' . DS);
define('MODELS_DIR', INCLUDES_DIR . 'models' . DS);
define('PLUGINS_DIR', 'plugins' . DS);
define('THEMES_DIR', 'themes' . DS);
define('VENDOR_DIR', INCLUDES_DIR . 'vendor' . DS);
