<?php

namespace Unamed\FrontController;
use Unamed\Interfaces;
{
	class Request {
		const DEFAULT_METHOD = 'GET';
		const DEFAULT_URI = '/';

		public $body = null;
	    public $headers = array();
	    public $method = null;
	    public $uri = null;

		public function __construct() {
			$this->body = $this->body();
	        $this->headers = $this->headers();
	        $this->method = $this->method();
	        $this->uri = $this->uri();
	    }
		public function Request() { $this->__construct(); }

		public function body() {
			if (is_null($this->body)) {
				$this->body = @file_get_contents('php://input');
			}
			return $this->body;
		}

		public function headers() {
			if ($this->headers = array()) {
				foreach ($_SERVER as $name => $value) { 
		           if (substr($name, 0, 5) == 'HTTP_') {
		               $this->headers[str_replace(
		               		' ',
		               		'-',
		               		ucwords(strtolower(str_replace(
		               			'_',
		               			' ',
		               			substr($name, 5)
		               		)))
		               	)] = $value;
		            } else if ($name == "CONTENT_TYPE") {
		               $this->headers["Content-Type"] = $value;
		            } else if ($name == "CONTENT_LENGTH") {
		               $this->headers["Content-Length"] = $value;
		            }
		        }
		    }
		    return $this->headers;
		}

		public function method() {
			if ($this->method == null) {
				$this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : self::DEFAULT_METHOD;
		        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
		            $this->method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
		        } else if (isset($_REQUEST['_method'])) {
		            $this->method = $_REQUEST['_method'];
		        }
		    }
			return $this->method;
		}

		public function uri() {
			if ($this->uri == null) {
				$this->uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : self::DEFAULT_URI;
		    }
			return $this->uri;
		}
	};
}
