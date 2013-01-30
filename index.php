<?php
session_start();
require './vendor/autoload.php';
include './includes/baseClasses.php';

$un = new Unamed();

include './includes/functions.php';
$un->init();
