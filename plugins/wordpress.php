<?php

class Wordpress extends Plugin {
	private $hookAliases = array(
		'muplugins_loaded' => 'ms_plugins_loaded',//
		'wp_register_sidebar_widget' => 'register_sidebar_widget',//
		'wp_default_scripts' => 'default_scripts',//
		'wp_default_styles' => 'default_styles',//
		'wp_loaded' => 'loaded',//
		'wp' => 'un',//
		'wp_head' => 'head',//
		'wp_enqueue_scripts' => 'enqueue_scripts',//
		'wp_print_styles' => 'print_styles',//
		'wp_print_scripts' => 'print_scripts',//
		'wp_meta' => 'meta',//
		'wp_footer' => 'footer',//
		'wp_print_footer_scripts' => 'print_footer_scripts',//
		'wp_before_admin_bar_render' => 'before_admin_bar_render',//
		'wp_after_admin_bar_render' => 'after_admin_bar_render',//
	);

	public function __construct() {

	}

	public function Wordpress() {
		$this->__construct();
	}
}