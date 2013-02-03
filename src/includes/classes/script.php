<?php

namespace Unamed {
	class Script {
		protected $handle = null;
		protected $src = null;
		protected $deps = null;
		protected $ver = null;
		protected $inFooter = null;
		protected $enable = null;

		public function __construct($handle, $src, $deps, 
			$ver, $inFooter, $enable) {
			$this->handle = $handle;
			$this->src = $src;
			$this->deps = $deps;
			$this->ver = $ver;
			$this->inFooter = $inFooter;
			$this->enable = $enable;
		}

		public function Script($handle, $src, $deps,
			$ver, $inFooter, $enable) {
			$this->__construct($handle, $src, $deps,
				$ver, $inFooter, $enable
			);
		}
	};
}