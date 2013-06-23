<?php

namespace Unamed\Controllers\Admin {
    class Base
    {
        public function __construct()
        {
            global $un;
            $un->enqueueScript('jquery', getAdminAssetsUrl() . 'js/jquery.min.js');
            $un->enqueueScript('unAdmin', getAdminAssetsUrl() . 'js/admin.js');
            $un->enqueueStyle('lava', getAdminAssetsUrl() . 'css/lava.css');
            $un->enqueueStyle('unAdmin', getAdminAssetsUrl() . 'css/admin.css');
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
            global $un;
            $this->params = $params;
            parent::__construct();
            $un->setViewData(
                array('posts' => $this->getPosts())
            );
        }
        private function getPosts() {
            global $un;
            $posts = array();
            $post_types = $un->getPostTypes();
            //if ($post_types[$this->target_post_type]['public'] == true) {
                $posts = \Model::factory('Post')->
            //        where('post_type', $this->target_post_type)->
            //        order_by('post_date')->
                    find_many();
                foreach ($posts as $post) {
                    $post->postmeta = $post->postmeta();
                }
            //}
            return $posts;
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
