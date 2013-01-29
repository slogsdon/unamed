<?php
if (!session_id()) die();
class Unamed {
	var $availablePlugins = array();
	public function __construct() {
		$this->getConfiguration();
		#$this->checkCache();
		$this->load('plugins');
		$this->requestHandler();
	}
	public function Unamed() {
		$this->__construct();
	}
	public function index() {
		print"<!-- build: ".substr(file_get_contents('./.git/refs/heads/master'), 0, 10)." -->";
	}
	private function load($dir) {
		$blocks = array();
		$dir = $dir.'/';
		// read $dir, add filenames to $blocks
		if ($d = dir($dir))
			while (false !== ($file = $d->read()))
				if ($file != "." && $file != ".." && !in_array($file, $blocks)
					&& substr($file,strlen($file)-4)==".php")
					$blocks[ucwords(substr($file,0,-4))]=$file;
		// include and create
		foreach ($blocks as $name=>$file) {
			include_once($dir.$file);
			if ($this->$name = new $name) $this->$name->name = $name;
			#if (method_exists($this->$name, "load")) array_push($this->availableNamespaces, $name);
			#else
			array_push($this->availablePlugins, $name);
		}
	}
	private function getConfiguration() {
		include('includes/config.php');
		foreach($config as $key=>$value)
			$this->$key = $value;
	}
	private function requestHandler() {
		$vars = explode("/",substr($_SESSION['request'],strlen($this->installDirectory.$this->urlMagicWord.'//')));
		$namespace = isset($vars[0]) ? $vars[0] : null;
		$topic = (isset($vars[1]) && $vars[1]!=''?$vars[1]:'NamespaceIndex');
		if (!$namespace)
			$this->index();
		else
			$this->$namespace->load($topic);
	}
};

class Plugin {
	var $name;
};

