<?php

class gitBuildInfo
{
    public function __construct()
    {
        global $un;
        $un->enqueue('postTemplateIncluded', array($this, 'getBuildInfo'));
    }

    public function getBuildInfo()
    {
        if (file_exists('../.git/refs/heads/deploy'))
            print "<!-- build: ".substr(file_get_contents('../.git/refs/heads/deploy'), 0, 10)." -->\n";
        else if (file_exists('../.git/refs/heads/master'))
            print "<!-- build: ".substr(file_get_contents('../.git/refs/heads/master'), 0, 10)." -->\n";

        return;
    }
};
