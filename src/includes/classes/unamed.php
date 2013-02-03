<?php

namespace Unamed {
	class Unamed {
		/* Properties */
		public $options = null;
		protected $actions = array();
		protected $cache = null;
		protected $cacheKey = null;
		protected $fc = null;
		protected $isAdmin = null;
		protected $pageHookRunOrder = array(
			'startup',
			'plugins_loaded',
			'setup_theme',
			'dispatch',
			'posts_selection',
			'template_included',
			'shutdown',
		);
		protected $post_types = array(
			'post' => array(
				'public' => true,
			),
		);
		protected $scripts = null;
		protected $selectedPosts = null;
		protecteed $styles = null;
		protected $theme = null;

		/* Methods */
		public function __construct($isAdmin = false) {
			$this->isAdmin = $isAdmin;
			$this->fc = new FrontController\FrontController();
			$this->cacheKey = $this->createCacheKey();
			if (!$this->isAdmin && 
				ENABLE_CACHE && 
				ENABLE_PAGE_CACHE &&
				class_exists('Cache') && 
				Cache::isHit($this->cacheKey)) {
				$this->fc->response->addHeader('X-Cached', 'true');
				$this->fc->deliver(Cache::get($this->cacheKey));
			} else {
				$this->startSession();
				$this->loadOptions();
				foreach ($this->pageHookRunOrder as $hook) {
					if (is_callable(array($this, $hook))) {
						$this->enqueue($hook, array($this, $hook));
					}
				}
			}
			return;
		}

		public function Unamed() {
			$this->__construct();
		}

		public function isAdmin() {
			return $this->isAdmin;
		}

		public function addRoute($route, $controller) {
			$this->fc->router->addRoute($route, $controller);
			return $this;
		}

		public function addRoutes(array $routes) {
			$this->fc->router->addRoutes($routes);
			return $this;
		}

		protected function startSession() {
			if (!session_id()) {
				session_start();
			}
			return $this;
		}

		public function run() {
			foreach ($this->pageHookRunOrder as $hook) {
				$this->execute($hook);
			}
			return;
		}

		public function enqueue($hook, $action) {
			$this->actions[$hook][] = $action;
			return;
		}

		public function execute($hook) {
			if (array_key_exists($hook, $this->actions)) {
				foreach ($this->actions[$hook] as $action) {
					if (is_callable($action)) {
						call_user_func($action);
					}
				}
			}
			return;
		}

		protected function loadPlugins() {
			// read $dir, add filenames
			$dir = 'plugins';
			if ($this->isAdmin) $dir = '../' . $dir;
			if ($d = dir($dir . DS)) {
				$dir .= DS;
				$a = array();
				while (false !== ($file = $d->read())) {
					if ($file != "." && $file != ".." && 
						!in_array($file, $a)) {
						if (substr($file, -4) == ".php") {
							$a[ucfirst(substr($file, 0, -4))] = $file;
						} else if (is_dir($dir . $file)) {
							$a[ucfirst($file)] = $file . DS . $file . '.php';
						}
					}
				}
				// include and create
				foreach ($a as $name => $file) {
					if (file_exists($dir . $file)) {
						include_once $dir . $file;
					}
				}
			} else if (file_exists($dir . '.php')) {
				include_once $dir . '.php';
			}
			return;
		}

		protected function createCacheKey() {
			return 'un_cache' . str_replace('/', '_', $_SERVER['REQUEST_URI']);
		}

		protected function loadOptions() {
			if (is_null($this->options)) {
				$this->options = new \stdClass();
				$options = \Model::factory('Option')->where('autoload', '1')->find_many();
				foreach($options as $option) {
					$name = $option->option_name;
					$this->options->$name = $option->option_value;
				}
			}
			return;
		}

		public function get_option($name) {
			$ret = null;
			if (!is_null($this->options->$name) && !empty($this->options->$name)) {
				$ret = $this->options->$name;
			} else {
				$option = \Model::factory('Option')->where('option_name', $name)->find_one();
				$ret = $option->option_value;
			}
			return $ret;
		}

		protected function checkThemeForTemplateFiles($themeDir) {
			$ret = array(
				'homepage' => file_exists($themeDir . 'homepage.php'),
				'functions'=> file_exists($themeDir . 'functions.php'),
			);
			foreach($this->post_types as $post_type => $postTypeOptions) {
				if ($postTypeOptions['public'] === true)
					$ret[$post_type] = file_exists($themeDir . $post_type . '.php');
			}
			return $ret;
		}

		public function is_home() {
			return true;
		}

		public function is_404() {
			return false;
		}

		public function set404() {
			$this->fc->response->setStatus(404);
			return $this;
		}

		public function setSelectedPosts($posts) {
			$this->selectedPosts = $posts;
			return $this;
		}

		public function getPostTypes() {
			return $this->post_types;
		}

		public function the_posts() {
			return $this->selectedPosts;
		}

		public function has_posts() {
			return !is_null($this->selectedPosts) && count($this->selectedPosts);
		}

		public function registerStyle( $handle, $src = '', $deps = array(), $ver = false, $media = false ) {
			$this->styles->enqueue(new Style(
				$handle,
				$src,
				$deps,
				$ver,
				$media,
				false
			));
		}

		public function enqueueStyle( $handle, $src = '', $deps = array(), $ver = false, $media = false ) {
			
		}

		public function registerScript( $handle, $src = '', $deps = array(), $ver = false, $inFooter = true ) {
			$this->scripts->enqueue(new Script(
				$handle,
				$src,
				$deps,
				$ver,
				$inFooter,
				false
			));
		}

		public function enqueueScript( $handle, $src = '', $deps = array(), $ver = false, $in_footer = true ) {
			
		}

		// Page Hook Methods
		protected function init() {
			ob_start();
			$this->scripts = new \SplQueue();
			$this->styles = new \SplQueue();
			$this->execute('post_init');
			return;
		}

		protected function plugins_loaded() {
			$this->execute('pre_plugins_loaded');
			$this->loadPlugins();
			$this->execute('post_plugins_loaded');
			return;
		}

		protected function setup_theme() {
			$this->execute('pre_setup_theme');
			if (!$this->isAdmin) {
				$this->theme = new \stdClass();
				$this->theme->dir = THEMES_DIR . $this->options->theme . DS;
				$this->theme->has = $this->checkThemeForTemplateFiles($this->theme->dir);
				if ($this->theme->has['functions']) {
					include_once $this->theme->dir . 'functions.php';
				}
			}
			$this->execute('post_setup_theme');
			return;
		}
		
		protected function dispatch() {
			$this->execute('pre_dispatch');
			$this->fc->dispatch();
			$this->execute('post_dispatch');
			return;
		}
		
		protected function template_included() {
			$this->execute('pre_template_included');
			if (!$this->isAdmin) {
				if ($this->theme->has['homepage'] && $this->is_home()) {
					include_once $this->theme->dir . 'homepage.php';
				}
			} else {
				include_once INCLUDES_DIR . 'frontend.php';
			}
			$this->execute('post_template_included');
			return;
		}

		protected function deliver() {
			$this->execute('pre_deliver');
			$buffer = ob_get_clean();
			$config = array(
				'indent' => true,
				'input-xml' => true,
				'wrap' => 200
			);
			$tidy = tidy_repair_string($buffer, $config, 'UTF8');
			if (!$this->isAdmin &&
				!$this->is_404() &&
				ENABLE_CACHE && 
				ENABLE_PAGE_CACHE &&
				class_exists('Cache')) {
				Cache::add($this->cacheKey, (string)$tidy);
			}
			$this->fc->deliver($tidy);
			$this->execute('post_deliver');
			return;
		}
	};
}