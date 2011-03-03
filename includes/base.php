<?php
if (!session_id()) die();
class Unamed {
	var $availableNamespaces = array();
	var $availableUtilities = array();
	public function __construct() {
		$this->getConfiguration();
		#$this->checkCache();
		$this->load('namespaces');
		$this->load('utilities');
		$this->requestHandler();
	}
	public function Unamed() {
		$this->__construct();
	}
	public function index() {
		foreach($this->availableNamespaces as $ns) {
			$url = $this->hostname.($this->$ns->needsAuthentication ? '#' : $this->urlMagicWord."/{$ns}:");
			print"<a href=\"{$url}\">$ns</a><br />\n";
		}
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
			if (method_exists($this->$name, "load")) array_push($this->availableNamespaces, $name);
			else array_push($this->availableUtilities, $name);
		}
	}
	private function getConfiguration() {
		include('includes/config.php');
		foreach($config as $key=>$value)
			$this->$key = $value;
	}
	private function requestHandler() {
		$vars = explode(":",substr($_SESSION['request'],strlen($this->installDirectory.$this->urlMagicWord.'//')));
		$namespace = $vars[0];
		$topic = ($vars[1]!=''?$vars[1]:'NamespaceIndex');
		if (!$namespace)
			$this->index();
		else
			$this->$namespace->load($topic);
	}
};
class Namespace {
	// children = sections under namespace
	var $name;
	var $isHidden = false;
	var $needsAuthentication = false;
	function __toString() {
		return "'".$this->name."' namespace";
	}
	function load($topic='NamespaceIndex') {
		if ($topic == 'NamespaceIndex')
			print $this;
		else
			print $this.$topic;
	}
	//hasChildren
};
class Utility {
	var $name;
};
?>
