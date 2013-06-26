<?php
/**
 * Unamed - a WordPress replacement
 *
 * Functions to make theme and plugin developers' lives easier if they choose
 * to use these
 *
 * @category CMS
 * @package  Unamed
 * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
 * @license  MIT http://mit.edu/
 * @link     http://bitbucket.org/slogsdon/unamed
 */

// set up autoloader
spl_autoload_register(function ($className) {
    $tmp = explode("\\", $className);
    $class = $tmp[count($tmp) - 1];
    unset($tmp);
    
    if (stristr(strtolower($className), 'interfaces'))
        $file = INTERFACES_DIR . $class . '.php';
    else if (stristr(strtolower($className), 'controllers'))
        $file = CONTROLLERS_DIR . $class . '.php';
    else if (stristr(strtolower($className), 'models'))
        $file = MODELS_DIR . $class . '.php';
    else
        $file = CLASSES_DIR . $class . '.php';
    
    if (is_readable($file))
        require_once $file;
});

/**
 * enqueue
 *
 * @param string   $hook   - 
 * @param callback $action -
 *
 * @return nothing
 */
function enqueue($hook, $action)
{
    global $un;
    $un->enqueue($hook, $action);
    return;
}

/**
 * execute
 *
 * @param string $hook - 
 *
 * @return nothing
 */
function execute($hook)
{
    global $un;
    $un->execute($hook);
    return;
}

/**
 * getAfterBody
 *
 * @return nothing
 */
function getAfterBody()
{
    execute('afterBody');
    return;
}

/**
 * enqueue
 *
 * @return nothing
 */
function getHeader()
{
    global $un;
    if (file_exists(THEMES_DIR . $un->options->theme . '/header.php')) {
        include THEMES_DIR . $un->options->theme . '/header.php';
    }
    return;
}

/**
 * getFooter
 *
 * @return nothing
 */
function getFooter()
{
    global $un;
    if (file_exists('./themes/' . $un->options->theme . '/footer.php')) {
        include THEMES_DIR . $un->options->theme . '/footer.php';
    }
    return;
}

/**
 * thePosts
 *
 * @return array(Post)
 */
function thePosts()
{
    global $un;
    return $un->thePosts();
}

/**
 * hasPosts
 *
 * @return bool
 */
function hasPosts()
{
    global $un;
    return $un->hasPosts();
}
