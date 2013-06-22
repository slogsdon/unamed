<?php

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
    // echo '<link href="' . getAdminAssetsUrl() 
    //     . 'css/lava.css" rel="stylesheet" type="text/css" />'
    //     . '<link href="' . getAdminAssetsUrl() 
    //     . 'css/admin.css" rel="stylesheet" type="text/css" />';
    global $un;
    $html = '';
    foreach ($un->getStyles() as $style)
    {
        $html .= '<link href="' . $style->src . '"'
              . ' rel="stylesheet"'
              . ' type="text/css"'
              . (!$style->media ? '' : 'media="' . $style->media . '"')
              . ' />' . "\n";
    }
    echo $html;
    return;
}

function getAdminFoot() 
{
    echo '<script src="' . getAdminAssetsUrl() 
        . 'js/jquery.min.js" type="text/javascript"></script>'
        . '<script src="' . getAdminAssetsUrl() 
        . 'js/admin.js" type="text/javascript"></script>';
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
    $data = $un->getRouteData();
    $controller = explode("\\", $data->controller);
    $base = BASE_DIR . ADMIN_DIR . 'templates/' . $controller[count($controller) - 1];
    if (file_exists($base . '.' . $data->action . '.php'))
        include_once $base . '.' . $data->action . '.php';
    else if (file_exists($base . '.php'))
        include_once $base . '.php';
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
