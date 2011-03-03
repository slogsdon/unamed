<?php
if (!session_id()) die();
class Admin extends Namespace {
	var $isHidden = true;
	var $needsAuthentication = true;
	function __construct() {
		$this->name = get_class();
	}
	function Admin() {
		$this->__construct();
	}
};
?>