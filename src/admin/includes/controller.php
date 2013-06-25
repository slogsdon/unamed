<?php

namespace Unamed\Controllers\Admin {
    class Base
    {
        public function __construct()
        {
            global $un;
            $un->enqueueScript('jquery', getAdminAssetsUrl() . 'js/jquery.min.js');
            // $un->enqueueScript('jquery.scrollto', getAdminAssetsUrl() . 'js/jquery-scrollto.js', array('jquery'));
            // $un->enqueueScript('jquery.history', getAdminAssetsUrl() . 'js/jquery.history.js', array('jquery.scrollto'));
            // $un->enqueueScript('ajaxify.html5', getAdminAssetsUrl() . 'js/ajaxify-html5.js', array('jquery.history'));
            $un->enqueueScript('bootstrap', getAdminAssetsUrl() . 'js/bootstrap.min.js', array('jquery'));
            $un->enqueueScript('un.admin', getAdminAssetsUrl() . 'js/admin.js', array('jquery'));

            $un->enqueueStyle('google-fonts', '//fonts.googleapis.com/css?family=Titillium+Web:200,200italic,400|Raleway:200,400,700');
            $un->enqueueStyle('bootstrap', getAdminAssetsUrl() . 'css/bootstrap.min.css');
            $un->enqueueStyle('bootstrap.flat', getAdminAssetsUrl() . 'css/bootstrap.flat.css', array('bootstrap'));
            $un->enqueueStyle('un.admin', getAdminAssetsUrl() . 'css/un-admin.css', array('bootstrap'));
            $un->enqueueStyle('bootstrap.responsive', getAdminAssetsUrl() . 'css/bootstrap-responsive.min.css', array('bootstrap'));
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
            if (!isset($params['action']))
                $un->setViewData(
                    array('posts' => $this->getPosts())
                );
        }
        
        public function edit() 
        {
            global $un;
            $un->enqueueScript('tinymce', '//tinymce.cachefly.net/4.0/tinymce.min.js');
            $un->setViewData(
                array('post' => $this->getPost($this->params['id']))
            );
        }
        
        private function getPost($id)
        {
            global $un;
            $post = \Model::factory('Post')->
                where('ID', $id)->
                find_one();
            return $post;
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
