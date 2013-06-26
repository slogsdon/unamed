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
        protected function PostToSql($key)
        {

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
            $post = $this->getPost($this->params['id']);

            if (count($_POST) > 0)
            {
                try 
                {
                    $post->UpdateWith($_POST)->save();
                    $un->addFlashMessage(
                        array(
                            'type' => 'success',
                            'message' => 'The post was updated.'
                        )
                    );
                }
                catch (Exception $e)
                {
                    $un->addFlashMessage(
                        array(
                            'type' => 'error',
                            'message' => 'There was an error updating the post.'
                        )
                    );
                }
            }

            $un->setViewData(
                array('post' => $post)
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
            global $un;
            $this->params = $params;
            parent::__construct();

            $un->setViewData(
                array('plugins' => $un->plugins)
            );
        }
    };
    class Themes extends Base
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            global $un;
            $this->params = $params;
            parent::__construct();

            $un->setViewData(
                array('themes' => $un->themes)
            );
        }
    };
    class Settings extends Base
    {
        protected $params = array();
        public function __construct(array $params = array())
        {
            global $un;
            $this->params = $params;
            parent::__construct();

            $un->setViewData(
                array('settings' => $this->getSettings())
            );
        }
        public function edit()
        {
        }

        private function getSettings()
        {
            $result = array();
            $options = \Model::factory('Option')->find_many();
            foreach ($options as $option) {
                $result[$option->option_name] = $option->option_value;
            }
            return $result;
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
