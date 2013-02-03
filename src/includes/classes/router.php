<?php

namespace Unamed\FrontController;
use Unamed\Interfaces;
{
	class Router implements Interfaces\Router {
		const CONTROLLER_NAMESPACE = 'Unamed\Controllers';
	    const DEFAULT_CONTROLLER = 'Post';
	    const DEFAULT_ACTION = null;
	    const DEFAULT_BASEPATH = null;
	    
	    protected $controller = self::DEFAULT_CONTROLLER;
	    protected $action = self::DEFAULT_ACTION;
	    protected $params = array();
	    protected $basePath = self::DEFAULT_BASEPATH;
	    protected $request = null;
	    protected $routes = array();

	    public function __construct(Request $request, array $options = array()) {
	    	$this->request = $request;
	    	if (isset($options['basePath'])) {
	    		$this->basePath = $options['basePath'];
	    		unset($options['basePath']);
	    	}
	    }

	    public function Router(Request $request, array $options = array()) {
	    	$this->__construct($request, $options);
	    }

		public function addRoute($route, $controller) {
			$this->routes[$route] = $controller;
			return $this;
		}

		public function addRoutes(array $routes = array()) {
			foreach ($routes as $route => $controller) {
				$this->addRoute($route, $controller);
			}
			return $this;
		}

		// Compiles a route string to a regular expression
		// lifted from https://github.com/chriso/klein.php
		protected function compileRoute($route) {
		    if (preg_match_all('`(/|\.|)\[([^:\]]*+)(?::([^:\]]*+))?\](\?|)`', $route, $matches, PREG_SET_ORDER)) {
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

	    public function getData() {
	        $this->parseUri();
	    	$o = new \stdClass();
	    	$o->controller = self::CONTROLLER_NAMESPACE . "\\" . $this->controller;
	    	$o->action = $this->action;
	    	$o->params = $this->params;
	    	return $o;
	    }

	    protected function parse($path, $regex) {
	    	return preg_match($regex, $path, $this->params);
	    }
	    
	    protected function parseUri() {
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
	    }
	    
	    public function setAction($action) {
	        $reflector = new \ReflectionClass(self::CONTROLLER_NAMESPACE . "\\" . $this->controller);
	        if ($reflector->hasMethod($action)) {
	        	$this->action = $action;
	        }
	        return $this;
	    }

	    public function setBasePath($basePath) {
	    	$this->basePath = $basePath;
	    	return $this;
	    }
	    
	    public function setController($controller) {
	        $controller = ucfirst(strtolower($controller));
	        if (class_exists(self::CONTROLLER_NAMESPACE . "\\" . $controller)) {
	            $this->controller = $controller;
	        }
	        return $this;
	    }
	    
	    public function setParams(array $params) {
	        $this->params = $params;
	        return $this;
	    }
	};
}