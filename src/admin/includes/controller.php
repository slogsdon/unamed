<?php

namespace Unamed\Controllers\Admin {
    class Overview
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
        }
    };
    class Posts
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
        }
    };
    class Plugins
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
        }
    };
    class Themes
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
        }
    };
    class Settings
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            $this->params = $params;
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
        }
    };
}
