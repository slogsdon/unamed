<?php
class Database extends Plugin {
	private function __connectToDatabase() {
		$this->mysqli = new mysql_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
		if (mysqli_connect_errno)
			$this->mysqli = false;
		return;
	}
};
?>
