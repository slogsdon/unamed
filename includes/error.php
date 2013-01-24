<?php
/*------------------------------------
     HTTP Error Handler - i.e. 404
  ------------------------------------*/
if (isset($_SERVER['REDIRECT_URL']))
	$_SESSION['request'] = $_SERVER['REDIRECT_URL'].$_GET['var'];

?>