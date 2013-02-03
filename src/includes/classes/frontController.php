<?php

namespace Unamed\FrontController;
use Unamed\Interfaces;
{
	class FrontController implements Interfaces\FrontController {
		public $dispatcher = null;
		public $request = null;
		public $response = null;
		public $router = null;

		public function __construct(array $options = array()) {

			$this->request = new Request();
			$this->response = new Response();
			$this->router = new Router($this->request);
		}

		public function FrontController(array $options = array()) {
			$this->__construct($options);
		}

		public function deliver($data) {
			$this->response->deliver($this->request, $data);
		}

		public function dispatch() {
			$data = $this->router->getData();
			$c = $data->controller;
			$ref = new $c($data->params);
			$a = $data->action;
			if (!is_null($a) && !empty($a)) call_user_func(array($ref, $a));
		}
	};
}
