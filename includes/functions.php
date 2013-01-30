<?php

function enqueue($hook, $action) {
	global $un;
	$un->enqueue($hook, $action);
}

function execute($hook) {
	global $un;
	$un->execute($hook);
}

function get_after_body() {
	execute('after_body');
}