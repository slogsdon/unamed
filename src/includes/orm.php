<?php
/**
 * Unamed - a WordPress replacement
 *
 * @category CMS
 * @package  Unamed
 * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
 * @license  MIT http://mit.edu/
 * @link     http://bitbucket.org/slogsdon/unamed
 */

ORM::configure('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME);
ORM::configure('username', DB_USER);
ORM::configure('password', DB_PASS);
ORM::configure('logging', DB_LOGGING);
Model::$auto_prefix_models = '\\Unamed\\Models\\';

foreach (glob(MODELS_DIR . '*.php') as $filename) {
    include_once $filename;
}
