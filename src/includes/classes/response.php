<?php

namespace Unamed\FrontController;
use Unamed\Interfaces;
{
	class Response {
		const DEFAULT_STATUS = 200;
		const HTTP_10 = 'HTTP/1.0';
		const HTTP_11 = 'HTTP/1.1';

		protected $headers = array();
		protected $options = array();
		protected $status = self::DEFAULT_STATUS;
		protected $use10 = false;

		public function __construct() {}
		public function Response() { $this->__construct(); }

		public function addHeader($name, $value, $replace = true) {
			$this->headers[] = array(
				'name' => $name,
				'value' => $value,
				'replace' => $replace,
			);
			return $this;
		}

		public function addHeaders(array $headers = array()) {
			foreach ($headers as $header) {
				$this->addHeader(
					$header['name'],
					$header['value'],
					isset($header['replace']) ? $header['replace'] : true
				);
			}
			return $this;
		}

		public function deliver($request, $data) {
			// set up
			if ($this->use10) {
				header(self::HTTP_10 . ' ' . $this->status);
			} else {
				header(self::HTTP_11 . ' ' . $this->status);
			}
			foreach ($this->headers as $header) {
				header($header['name'] . ': ' . $header['value'], $header['replace']);
			}

			// deliver
			if (is_callable($data)) {
				call_user_func($data);
			} else {
				echo $data;
			}
			return;
		}

		public function noCache() {
			$this->addHeaders(array(
				array(
					'name' => 'Cache-Control',
					'value' => 'no-store, no-cache, must-revalidate'
				),
				array(
					'name' => 'Cache-Control',
					'value' => 'post-check=0, pre-check=0',
					'replace' => false
				), 
				array(
					'name' => 'Pragma',
					'value' => 'no-cache'
				),
			));
			return $this;
		}

		public function setOptions(array $options) {
			$this->options = $options;
			return $this;
		}

		public function setStatus($status) {
			if (is_integer($status))
				$this->status = $status;
			return $this;
		}

		public function useHttp10() {
			if (function_exists('apache_setenv')) {
		        apache_setenv('downgrade-1.0','true');
		        apache_setenv('force-response-1.0','true');
		    }
			$this->use10 = true;
			return $this;
		}

		public function useHttp11() {
			if (function_exists('apache_setenv')) {
		        apache_setenv('downgrade-1.0','false');
		        apache_setenv('force-response-1.0','false');
		    }
			$this->use10 = false;
			return $this;
		}
	};
}
