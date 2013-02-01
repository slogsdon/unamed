<?php

class Cache {
	public static function add($key, $data) {
		$file = md5($key);
		$path = substr($file, 0, 1) . '/' . substr($file, 0, 2) . '/';
		if (!is_dir($path) && !file_exists($path)) mkdir(CACHE_DIR . $path, 0777, true);
		return file_put_contents(CACHE_DIR . $path . $file, $data);
	}

	public static function get($key) {
		$file = md5($key);
		$path = substr($file, 0, 1) . '/' . substr($file, 0, 2) . '/';
		return file_get_contents(CACHE_DIR . $path . $file);
	}

	public static function is_hit($key) {
		$file = md5($key);
		$path = substr($file, 0, 1) . '/' . substr($file, 0, 2) . '/';
		return file_exists(CACHE_DIR . $path . $file);
	}
}