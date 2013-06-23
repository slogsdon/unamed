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

namespace Unamed\FrontController;
use Unamed\Interfaces;
{
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
    class Router implements Interfaces\Router
    {
        /* Constants */
        const CONTROLLER_NAMESPACE = 'Unamed\Controllers';
        const DEFAULT_CONTROLLER = 'Post';
        const DEFAULT_ACTION = null;
        const DEFAULT_BASEPATH = null;

        /* Properties */
        protected $controller = self::DEFAULT_CONTROLLER;
        protected $action = self::DEFAULT_ACTION;
        protected $params = array();
        protected $basePath = self::DEFAULT_BASEPATH;
        protected $request = null;
        protected $routes = array();

        /* Methods */
        /**
         * __construct
         *
         * @param Request $request - 
         * @param array   $options - (opt.)
         */
        public function __construct(Request $request, array $options = array())
        {
            $this->request = $request;
            if (isset($options['basePath'])) {
                $this->basePath = $options['basePath'];
                unset($options['basePath']);
            }
        }

        /**
         * addRoute
         *
         * @param string $route      - 
         * @param string $controller - 
         *
         * @return object(Router)
         */
        public function addRoute($route, $controller)
        {
            $this->routes[$route] = $controller;

            return $this;
        }

        /**
         * addRoutes
         *
         * @param array $routes - 
         *
         * @return object(Router)
         */
        public function addRoutes(array $routes)
        {
            foreach ($routes as $route => $controller) {
                $this->addRoute($route, $controller);
            }

            return $this;
        }

        /**
         * comileRoute
         *
         * Compiles a route string to a regular expression
         * lifted from https://github.com/chriso/klein.php because i hate
         * dealing with regex
         *
         * @param string $route - 
         *
         * @return string
         */
        protected function compileRoute($route)
        {
            if (preg_match_all(
                '`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', 
                $route, 
                $matches, 
                PREG_SET_ORDER
            )) {
                $match_types = array(
                    'i'  => '[0-9]++',
                    'a'  => '[0-9A-Za-z]++',
                    'h'  => '[0-9A-Fa-f]++',
                    '*'  => '.+?',
                    '**' => '.++',
                    ''   => '[^/]++'
                );
                foreach ($matches as $match) {
                    list($block, $pre, $type, $param, $optional) = $match;

                    if (isset($match_types[$type])) {
                        $type = $match_types[$type];
                    }
                    if ($pre === '.') {
                        $pre = '\.';
                    }
                    // Older versions of PCRE require the 'P' in (?P<named>)
                    $pattern = '(?:'
                             . ($pre !== '' ? $pre : null)
                             . '('
                             . ($param !== '' ? "?P<".$param.">" : null)
                             . $type
                             . '))'
                             . ($optional !== '' ? '?' : null);

                    $route = str_replace($block, $pattern, $route);
                }
            }
            return '`^'.$route.'$`';
        }

        /**
         * getData
         *
         * @return object
         */
        public function getData()
        {
            $this->parseUri();
            $o = new \stdClass();
            $o->controller = self::CONTROLLER_NAMESPACE 
                . "\\" . $this->controller;
            $o->action = $this->action;
            $o->params = $this->params;
            return $o;
        }

        /**
         * parse
         *
         * run regex against request uri and pull out params (if matched)
         *
         * @param string $path  - request uri
         * @param string $regex - compiled route
         *
         * @return int
         */
        protected function parse($path, $regex)
        {
            $params = array();
            $result = preg_match($regex, $path, $params);
            print_r($params);die();
            if (count($params))
                $this->params = $params;
            return $result;
        }

        /**
         * parseUri
         *
         * tries to match the request uri to a route
         *
         * @return nothing
         */
        protected function parseUri()
        {
            $path = rtrim(parse_url($this->request->uri, PHP_URL_PATH), '/');
            if (!is_null($this->basePath)) {
                if (strpos($path, $this->basePath) === 0) {
                    $path = substr($path, strlen($this->basePath));
                }
            }
            foreach ($this->routes as $route => $controller) {
                if (ENABLE_CACHE && \Unamed\Cache::isHit('route:' . $route)) {
                    $routeRegEx = \Unamed\Cache::get('route:' . $route);
                } else {
                    $routeRegEx = $this->compileRoute($route);
                }
                if ($this->parse($path, $routeRegEx) === 1) {
                    $this->setController($controller);
                    if (isset($this->params['action'])) {
                        $this->setAction($this->params['action']);
                    }
                    continue;
                }
            }
            return;
        }

        /**
         * setAction
         *
         * @param bool $action - target action
         *
         * @return object(Router)
         */
        public function setAction($action)
        {
            $reflector = new \ReflectionClass(
                self::CONTROLLER_NAMESPACE . "\\" . $this->controller
            );
            if ($reflector->hasMethod($action)) {
                $this->action = $action;
            }
            return $this;
        }

        /**
         * setBasePath
         *
         * @param bool $basePath - any sub-directory structure
         *
         * @return object(Router)
         */
        public function setBasePath($basePath)
        {
            $this->basePath = $basePath;
            return $this;
        }

        /**
         * setController
         *
         * @param string $controller - target controller
         *
         * @return object(Router)
         */
        public function setController($controller)
        {
            $controller = ucfirst(strtolower($controller));
            if (class_exists(
                self::CONTROLLER_NAMESPACE . "\\" . $controller
            )) {
                $this->controller = $controller;
            }
            return $this;
        }

        /**
         * setParams
         *
         * @param array $params - route params
         *
         * @return object(Router)
         */
        public function setParams(array $params)
        {
            $this->params = $params;
            return $this;
        }
    };
}
