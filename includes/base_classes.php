<?php
class Unamed {
	/* Properties */
	public $options = null;
	private $actions = array();
	private $cache = null;
	private $cache_key = null;
	private $page_hook_run_order = array(
		'startup',
		'ms_plugins_loaded',
		'registered_taxonomy',
		'registered_post_type',
		'plugins_loaded',
		'sanitize_comment_cookies',
		'setup_theme',
		'load_textdomain',
		'after_setup_theme',
		'auth_cookie_malformed',
		'auth_cookie_valid',	
		'set_current_user',
		'init',
		//└─ widgets_init
		'register_sidebar',
		'register_sidebar_widget',
		'default_scripts',
		'default_styles',
		'admin_bar_init',
		'add_admin_bar_menus',
		'loaded',
		'parse_request',
		'send_headers',
		'parse_query',
		'pre_get_posts',
		'posts_selection',
		'un',
		'template_redirect',
		'get_header',
		'head',
		'enqueue_scripts',
		'print_styles',
		'print_scripts',
		'get_search_form',
		'loop_start',
		'the_post',
		'get_template_part_content',
		'loop_end',
		'get_sidebar',
		'dynamic_sidebar',
		'pre_get_comments',
		'meta',
		'get_footer',
		'footer',
		'print_footer_scripts',
		'admin_bar_menu',
		'before_admin_bar_render',
		'admin_bar_render',
		'after_admin_bar_render',
		'shutdown',
	);
	private $post_types = array(
		'post',
	);
	private $selected_posts = null;
	private $target_post_type = 'post';
	private $theme = null;

	/* Methods */
	public function __construct() {
		$this->cache_key = $this->create_cache_key();
		if (ENABLE_CACHE && 
			class_exists('Cache') && 
			Cache::is_hit($this->cache_key)) {
			header('X-Cached: true');
			print Cache::get($this->cache_key);
		} else {
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

	private function load($dir) {
		// read $dir, add filenames
		if ($d = dir($dir . '/')) {
			$dir .= '/';
			$a = array();
			while (false !== ($file = $d->read())) {
				if ($file != "." && $file != ".." && 
					!in_array($file, $a)) {
					if (substr($file, -4) == ".php") {
						$a[ucfirst(substr($file, 0, -4))] = $file;
					} else if (is_dir($dir . $file)) {
						$a[ucfirst($file)] = $file . '/' . $file . '.php';
					}
				}
			}
			// include and create
			foreach ($a as $name => $file) {
				if (file_exists($dir . $file)) {
					include_once $dir . $file;
				}
				if (class_exists($name) && $this->$name = new $name) {
					$this->$name->name = $name;
				}
			}
		} else if (file_exists($dir . '.php')) {
			include_once $dir . '.php';
		}
		return;
	}

	private function create_cache_key() {
		return 'un_cache' . str_replace('/', '_', $_SERVER['REQUEST_URI']);
	}

	private function load_options() {
		if (is_null($this->options)) {
			$this->options = new stdClass();
			$options = Model::factory('Option')->where('autoload', '1')->find_many();
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
			$option = Model::factory('Option')->where('option_name', $name)->find_one();
			$ret = $option->option_value;
		}
		return $ret;
	}

	private function check_theme_files($theme_dir) {
		$ret = array(
			'homepage' => file_exists($theme_dir . 'homepage.php'),
			'functions'=> file_exists($theme_dir . 'functions.php'),
		);
		foreach($this->post_types as $post_type) {
			$ret[$post_type] = file_exists($theme_dir . $post_type . '.php');
		}
		return $ret;
	}

	public function is_home() {
		return true;
	}

	public function the_posts() {
		return $this->selected_posts;
	}

	public function has_posts() {
		return !is_null($this->selected_posts) && count($this->selected_posts);
	}

	// Page Hook Methods
	private function startup() {
		ob_start('ob_tidyhandler');
		return;
	}

	private function plugins_loaded() {
		$this->load('plugins');
		return;
	}

	private function setup_theme() {
		$this->theme = new stdClass();
		$this->theme->dir = THEMES_DIR . $this->options->theme . '/';
		$this->theme->has = $this->check_theme_files($this->theme->dir);
		if ($this->theme->has['functions']) {
			include_once $this->theme->dir . 'functions.php';
		}
		return;
	}
	
	private function init() {
		$this->execute('widgets_init');
		return;
	}

	private function posts_selection() {
		$posts = Model::factory('Post')->
			where('post_type', $this->target_post_type)->
			order_by_desc('post_date')->
			find_many();
		foreach ($posts as $post) {
			$post->postmeta = $post->postmeta();
		}
		$this->selected_posts = $posts;
		return;
	}
	
	private function template_redirect() {
		if ($this->theme->has['homepage'] && $this->is_home()) {
			include_once $this->theme->dir . 'homepage.php';
		}
		return;
	}

	private function shutdown() {
		$buffer = ob_get_clean();
		$config = array(
			'indent' => true,
			'input-xml' => true,
			'wrap' => 200
		);
		$tidy = tidy_repair_string($buffer, $config, 'UTF8');
		if (ENABLE_CACHE && class_exists('Cache')) Cache::add($this->cache_key, (string)$tidy);
		echo $tidy;
		return;
	}
};

class Cache {
	public static function add($key, $data) {
		return file_put_contents(CACHE_DIR . $key, $data);
	}

	public static function get($key) {
		return file_get_contents(CACHE_DIR . $key);
	}

	public static function is_hit($key) {
		return file_exists(CACHE_DIR . $key);
	}
}
