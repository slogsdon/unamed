<?php

namespace Unamed {
	class Style {
		protected $handle = null;
		protected $src = null;
		protected $deps = null;
		protected $ver = null;
		protected $media = null;
		protected $enable = null;

		public function __construct($handle, $src, $deps,
			$ver, $media, $enable) {
			$this->handle = $handle;
			$this->src = $src;
			$this->deps = $deps;
			$this->ver = $ver;
			$this->media = $media;
			$this->enable = $enable;
		}

		public function Style($handle, $src, $deps,
			$ver, $media, $enable) {
			$this->__construct($handle, $src, $deps,
				$ver, $media, $enable
			);
		}
	};
}