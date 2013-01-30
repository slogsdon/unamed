<?php
if (!session_id()) die();
class Unamed {
	/* Properties */
	private $actions = array();
	public $options = null;
	private $post_types = array(
		'post',
	);
	private $target_post_type = 'post';
	private $selected_posts = null;
	private $page_hook_run_order = array(
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

	/* Methods */
	public function __construct() {
		$this->load_options();
		#$this->checkCache();
		foreach ($this->page_hook_run_order as $hook) {
			if (is_callable(array($this, $hook))) {
				$this->enqueue($hook, array($this, $hook));
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
		// read $dir, add filenames to $blocks
		if ($d = dir($dir . '/')) {
			$dir .= '/';
			$blocks = array();
			while (false !== ($file = $d->read())) {
				if ($file != "." && $file != ".." && 
					!in_array($file, $blocks) && 
					substr($file, strlen($file) - 4) == ".php") {
					$blocks[ucfirst(substr($file, 0, -4))] = $file;
				}
			}
			// include and create
			foreach ($blocks as $name => $file) {
				include_once $dir . $file;
				if ($this->$name = new $name) {
					$this->$name->name = $name;
				}
			}
		} else if (file_exists($dir . '.php')) {
			include_once $dir . '.php';
		}
		return;
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

	public function get_the_posts() {
		return $
	}

	// Page Hook Methods	
	private function plugins_loaded() {
		$this->load('plugins');
		return;
	}
	
	private function setup_theme() {
		$theme_dir = THEMES_DIR . $this->options->theme . '/';
		$has = $this->check_theme_files($theme_dir);
		if ($has['functions']) {
			include_once $theme_dir . 'functions.php';
		}
		if ($has['homepage'] && $this->is_home()) {
			include_once $theme_dir . 'homepage.php';
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
};

class Plugin {
	var $name;
};

