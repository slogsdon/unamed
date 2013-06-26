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

namespace Unamed\Interfaces {
    /**
     * Router implementation
     *
     * @category Class
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     * @since    1.0
     */
    interface Router
    {
        /**
         * addRoute
         *
         * @param string $route      - 
         * @param string $controller - 
         *
         * @return object(Router)
         */
        function addRoute($route, $controller);

        /**
         * addRoutes
         *
         * @param array $routes - 
         *
         * @return object(Router)
         */
        function addRoutes(array $routes);

        /**
         * getData
         *
         * @return object
         */
        function getData();

        /**
         * setAction
         *
         * @param bool $action - target action
         *
         * @return object(Router)
         */
        function setAction($action);

        /**
         * setBasePath
         *
         * @param bool $basePath - any sub-directory structure
         *
         * @return object(Router)
         */
        function setBasePath($basePath);

        /**
         * setController
         *
         * @param string $controller - target controller
         *
         * @return object(Router)
         */
        function setController($controller);

        /**
         * setParams
         *
         * @param array $params - route params
         *
         * @return object(Router)
         */
        function setParams(array $params);
    };
}
