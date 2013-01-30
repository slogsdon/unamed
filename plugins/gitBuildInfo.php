<?php

class GitBuildInfo extends Plugin {
	public function __construct() {
		enqueue('after_body', array($this, 'getBuildInfo'));
	}

	public function GitBuildInfo() {
		$this->__construct();
	}

	public function getBuildInfo() {
		$ret = '';
		if (file_exists('./.git/refs/heads/deploy'))
			print "<!-- build: ".substr(file_get_contents('./.git/refs/heads/deploy'), 0, 10)." -->";
		else if (file_exists('./.git/refs/heads/master'))
			print "<!-- build: ".substr(file_get_contents('./.git/refs/heads/master'), 0, 10)." -->";
		return;
	}
};
