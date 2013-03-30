<?php

namespace Unamed\Interfaces {
    interface Router
    {
        function addRoute($route, $controller);
        function addRoutes(array $routes);
        function setAction($action);
        function setController($controller);
        function setParams(array $params);
    };
}
