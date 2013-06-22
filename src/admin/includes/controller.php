<?php

namespace Unamed\Controllers\Admin {
    class Base
    {
        public function __construct()
        {
            enqueueScript('jquery', '/admin/assets/js/jquery.min.js');
            enqueueScript('admin', '/admin/assets/js/admin.js');
            enqueueStyle('lava', '/admin/assets/css/lava.css');
            enqueueStyle('admin', '/admin/assets/css/admin.css');
        }
    };
    class Overview extends Base
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
            parent::__construct();
        }
    };
    class Posts
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
            parent::__construct();
        }
    };
    class Plugins
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
            parent::__construct();
        }
    };
    class Themes
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
            parent::__construct();
        }
    };
    class Settings
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
            parent::__construct();
        }
        public function edit()
        {
        }
    };
    class Users
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
            parent::__construct();
        }
    };
}
