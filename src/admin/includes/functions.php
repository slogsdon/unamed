<?php

spl_autoload_register(function ($className) {
    $tmp = explode("\\", $className);
    $class = $tmp[count($tmp) - 1];
    unset($tmp);
    
    if (stristr(strtolower($className), 'interfaces'))
        $file = '../' . INTERFACES_DIR . $class . '.php';
    else if (stristr(strtolower($className), 'controllers'))
        $file = '../' . CONTROLLERS_DIR . $class . '.php';
    else if (stristr(strtolower($className), 'models'))
        $file = '../' . MODELS_DIR . $class . '.php';
    else
        $file = '../' . CLASSES_DIR . $class . '.php';
    
    if (is_readable($file))
        require_once $file;
});

function getAdminAssetsUrl()
{
    $path = '/' . str_replace(DS, '/', ADMIN_DIR) . 'assets/';
    return $path;
}

function adminAssetsUrl()
{
    echo getAdminAssetsUrl();
    return;
}

function adminUrl()
{
    $path = '/' . str_replace(DS, '/', ADMIN_DIR);
    echo $path;

    return;
}

function getAdminHead() 
{
    global $un;
    $html = '';
    foreach ($un->getStyles() as $style)
    {
        $html .= '<link href="' . $style->src . '" '
              . 'rel="stylesheet" '
              . 'type="text/css" '
              . (!$style->media ? '' : 'media="' . $style->media . '" ')
              . '/>' . "\n";
    }
    foreach ($un->getScripts(false) as $script) {
        $html .= '<script type="text/javascript" '
              . 'src="' . $script->src . '"></script>' . "\n";
    }
    echo $html;
    return;
}

function getAdminFoot() 
{
    global $un;
    $html = '';
    foreach ($un->getScripts() as $script) {
        $html .= '<script type="text/javascript" '
              . 'src="' . $script->src . '"></script>' . "\n";
    }
    echo $html;
    return;
}

function getHeader()
{
    if (file_exists(BASE_DIR . ADMIN_DIR . 'templates/header.php'))
        include_once BASE_DIR . ADMIN_DIR . 'templates/header.php';
    return;
}

function getFooter()
{
    if (file_exists(BASE_DIR . ADMIN_DIR . 'templates/footer.php'))
        include_once BASE_DIR . ADMIN_DIR . 'templates/footer.php';
    return;
}

function getContent()
{
    global $un;
    $viewData = $un->getViewData();
    if ($viewData !== array()) {
        foreach ($viewData as $k => $v)
            $$k = $v;
    }
    $data = $un->getRouteData();
    $controller = explode("\\", $data->controller);
    $base = BASE_DIR . ADMIN_DIR . 'templates/' . $controller[count($controller) - 1];
    if (file_exists($base . '.' . $data->action . '.php'))
        include_once $base . '.' . $data->action . '.php';
    else if (file_exists($base . '.php'))
        include_once $base . '.php';
    if ($viewData !== array()) {
        foreach ($viewData as $k => $v)
            $$k = $v;
            }
    return;
}

function registerStyle( $handle, $src = '', $deps = array(), $ver = false, $media = false )
{
    global $un;
    $un->registerStyle($handle, $src, $deps, $ver, $media);
}

function enqueueStyle( $handle, $src = '', $deps = array(), $ver = false, $media = false )
{
    global $un;
    $un->enqueueStyle($handle, $src, $deps, $ver, $media);
}

function registerScript( $handle, $src = '', $deps = array(), $ver = false, $in_footer = true )
{
    global $un;
    $un->registerScript($handle, $src, $deps, $ver, $in_footer);
}

function enqueueScript( $handle, $src = '', $deps = array(), $ver = false, $in_footer = true )
{
    global $un;
    $un->enqueueScript($handle, $src, $deps, $ver, $in_footer);
}

function getAdminAlerts()
{
    global $un;
    $messages = $un->getFlashMessages();
    foreach ($messages as $message)
    {
        echo '<div class="alert alert-' . $message['type'] . '">' 
            . '<button type="button" class="close" data-dismiss="alert">×</button>'
            . $message['message'] . '</div>' . "\n";
    }
}