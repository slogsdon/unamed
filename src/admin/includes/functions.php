<?php

function admin_assets_url() {
	$path = '/' . str_replace(DS, '/', ADMIN_DIR) . 'assets/';
	echo $path;
	return;
}

function admin_url() {
	$path = '/' . str_replace(DS, '/', ADMIN_DIR);
	echo $path;
	return;
}

function register_style( $handle, $src = '', $deps = array(), $ver = false, $media = false ) {
	global $un;
	
}

function enqueue_style( $handle, $src = '', $deps = array(), $ver = false, $media = false ) {
	global $un;
}

function register_script( $handle, $src = '', $deps = array(), $ver = false, $in_footer = true ) {
	global $un;
}

function enqueue_script( $handle, $src = '', $deps = array(), $ver = false, $in_footer = true ) {
	global $un;
}