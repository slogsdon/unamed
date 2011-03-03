<?php
if (!session_id()) die();
class About extends Namespace {
	var $title = 'varchar:50';
	var $body  = 'text';
};
?>