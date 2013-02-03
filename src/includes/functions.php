<?php

function enqueue($hook, $action) {
	global $un;
	$un->enqueue($hook, $action);
}

function execute($hook) {
	global $un;
	$un->execute($hook);
}

// theme functions
function get_after_body() {
	execute('after_body');
}

function get_header() {
	global $un;
	if (file_exists(THEMES_DIR . $un->options->theme . '/header.php')) {
		include THEMES_DIR . $un->options->theme . '/header.php';
	}
	return;
}

function get_footer() {
	global $un;
	if (file_exists('./themes/' . $un->options->theme . '/footer.php')) {
		include THEMES_DIR . $un->options->theme . '/footer.php';
	}
	return;
}

function the_posts() {
	global $un;
	return $un->the_posts();
}

function has_posts() {
	global $un;
	return $un->has_posts();
}
