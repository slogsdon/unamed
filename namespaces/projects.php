<?php
if (!session_id()) die();
class Projects extends Namespace {
	var $title      = 'varchar:50';
	var $link       = 'text';
	var $type       = 'varchar:50';
	var $screenshot = 'varchar:100';
	function __construct() {
		$this->name = get_class();
	}
	function Projects() {
		$this->__construct();
	}
};
?>