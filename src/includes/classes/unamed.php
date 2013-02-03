<?php

namespace Unamed {
	class Unamed {
		/* Properties */
		public $options = null;
		protected $actions = array();
		protected $cache = null;
		protected $cacheKey = null;
		protected $fc = null;
		protected $page_hook_run_order = array(
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
		protected $selected_posts = null;
		protected $theme = null;

		/* Methods */
		public function __construct() {
			$this->fc = new FrontController\FrontController();
			//print_r($this->fc);die();
			$this->cache_key = $this->create_cache_key();
			if (ENABLE_CACHE && 
				class_exists('Cache') && 
				Cache::is_hit($this->cache_key)) {
				$this->fc->response->addHeader('X-Cached', 'true');
				$this->fc->deliver(Cache::get($this->cache_key));
			} else {
				$this->fc->router->addRoute('/api/[*]?', 'Api');
				//session_start();
				$this->load_options();
				foreach ($this->page_hook_run_order as $hook) {
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

		public function run() {
			foreach ($this->page_hook_run_order as $hook) {
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

		protected function load($dir) {
			// read $dir, add filenames
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

		protected function create_cache_key() {
			return 'un_cache' . str_replace('/', '_', $_SERVER['REQUEST_URI']);
		}

		protected function load_options() {
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

		protected function check_theme_files($theme_dir) {
			$ret = array(
				'homepage' => file_exists($theme_dir . 'homepage.php'),
				'functions'=> file_exists($theme_dir . 'functions.php'),
			);
			foreach($this->post_types as $post_type => $post_type_options) {
				if ($post_type_options['public'] === true)
					$ret[$post_type] = file_exists($theme_dir . $post_type . '.php');
			}
			return $ret;
		}

		public function isHome() {
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
			$this->selected_posts = $posts;
			return $this;
		}

		public function getPostTypes() {
			return $this->post_types;
		}

		public function the_posts() {
			return $this->selected_posts;
		}

		public function has_posts() {
			return !is_null($this->selected_posts) && count($this->selected_posts);
		}

		// Page Hook Methods
		protected function startup() {
			ob_start();
			return;
		}

		protected function plugins_loaded() {
			$this->load('plugins');
			return;
		}

		protected function setup_theme() {
			$this->theme = new \stdClass();
			$this->theme->dir = THEMES_DIR . $this->options->theme . DS;
			$this->theme->has = $this->check_theme_files($this->theme->dir);
			if ($this->theme->has['functions']) {
				include_once $this->theme->dir . 'functions.php';
			}
			return;
		}
		
		protected function dispatch() {
			$this->execute('pre_dispatch');
			$this->fc->dispatch();
			$this>execute('post_dispatch');
			return;
		}
		
		protected function template_included() {
			$this>execute('pre_template_included');
			if ($this->theme->has['homepage'] && $this->isHome()) {
				include_once $this->theme->dir . 'homepage.php';
			}
			$this>execute('post_template_included');
			return;
		}

		protected function shutdown() {
			$buffer = ob_get_clean();
			$config = array(
				'indent' => true,
				'input-xml' => true,
				'wrap' => 200
			);
			$tidy = tidy_repair_string($buffer, $config, 'UTF8');
			if (ENABLE_CACHE && 
				class_exists('Cache') &&
				!$this->is_404()) {
				Cache::add($this->cache_key, (string)$tidy);
			}
			$this->fc->deliver($tidy);
			return;
		}
	};
}