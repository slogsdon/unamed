<?php
if (!session_id()) die();
class Unamed {
	/* Properties */
	// loaded plugins
	var $availablePlugins = array();
	// array(
	//   'hook' => array(
	//     mixed,
	//     ...
	//   ),
	// )
	var $actions = array();

	/* Methods */
	public function __construct() {	}

	public function Unamed() {
		$this->__construct();
	}

	public function init() {
		$this->getConfiguration();
		#$this->checkCache();
		$this->load('plugins');
		$this->requestHandler();
		include './themes/' . $this->theme . '/page.php';
	}

	public function enqueue($hook, $action) {
		$this->actions[$hook][] = $action;
		return;
	}

	public function execute($hook) {
		if (array_key_exists($hook, $this->actions)) {
			foreach ($this->actions[$hook] as $action) {
				call_user_func($action);
			}
		}
		return;
	}

	private function load($dir) {
		// read $dir, add filenames to $blocks
		if ($d = dir($dir . '/')) {
			$dir .= '/';
			$blocks = array();
			while (false !== ($file = $d->read())) {
				if ($file != "." && $file != ".." && !in_array($file, $blocks) 
					&& substr($file, strlen($file) - 4) == ".php") {
					$blocks[ucfirst(substr($file, 0, -4))] = $file;
				}
			}
			// include and create
			foreach ($blocks as $name => $file) {
				include_once $dir . $file;
				if ($this->$name = new $name) {
					$this->$name->name = $name;
					array_push($this->availablePlugins, $name);
				}
			}
		} else if (file_exists($dir . '.php')) {
			include $dir . '.php';
		}
		return;
	}

	private function getConfiguration() {
		include './includes/config.php';
		foreach($config as $key=>$value) {
			$this->$key = $value;
		}
		return;
	}

	private function requestHandler() {
		$namespace = isset($vars[0]) ? $vars[0] : null;
		//$topic = (isset($vars[1]) && $vars[1] != '' ? $vars[1] : 'NamespaceIndex');
		return;
	}
};

class Plugin {
	var $name;
};

