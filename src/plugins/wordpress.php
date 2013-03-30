<?php

class wordpress
{
    protected $hooks_to_add = array(
        'muplugins_loaded',
        'registered_taxonomy',
        'registered_post_type',
        'sanitize_comment_cookies',
        'load_textdomain',
        'after_setup_theme',
        'auth_cookie_malformed',
        'auth_cookie_valid',
        'set_current_user',
        //└─ widgets_init
        'register_sidebar',
        'wp_register_sidebar_widget',
        'wp_default_scripts',
        'wp_default_styles',
        'admin_bar_init',
        'add_admin_bar_menus',
        'wp_loaded',
        'parse_request',
        'send_headers',
        'parse_query',
        'pre_get_posts',
        'wp',
        'template_redirect', //
        'get_header',
        'wp_head',
        'wp_enqueue_scripts',
        'wp_print_styles',
        'wp_print_scripts',
        'get_search_form',
        'loop_start',
        'the_post',
        'get_template_part_content',
        'loop_end',
        'get_sidebar',
        'dynamic_sidebar',
        'pre_get_comments',
        'wp_meta',
        'get_footer',
        'wp_footer',
        'wp_print_footer_scripts',
        'admin_bar_menu',
        'wp_before_admin_bar_render',
        'admin_bar_render',
        'wp_after_admin_bar_render',
    );

    public function __construct()
    {
    }

    public function Wordpress()
    {
        $this->__construct();
    }
}
