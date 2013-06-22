<?php

namespace Unamed\Controllers\Admin {
    class Base
    {
        public function __construct()
        {
            global $un;
            $un->enqueueScript('jquery', getAdminAssetsUrl() . 'js/jquery.min.js');
            $un->enqueueScript('admin', getAdminAssetsUrl() . 'js/admin.js');
            $un->enqueueStyle('lava', getAdminAssetsUrl() . 'css/lava.css');
            $un->enqueueStyle('admin', getAdminAssetsUrl() . 'css/admin.css');
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
    class Posts extends Base
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
            parent::__construct();
        }
    };
    class Plugins extends Base
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
            parent::__construct();
        }
    };
    class Themes extends Base
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
            parent::__construct();
        }
    };
    class Settings extends Base
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
    class Users extends Base
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
            parent::__construct();
        }
    };
}
