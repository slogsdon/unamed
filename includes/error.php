<?php
/*------------------------------------
     HTTP Error Handler - i.e. 404
  ------------------------------------*/
$_SESSION['request'] = $_SERVER['REDIRECT_URL'].$_GET['var'];

?>