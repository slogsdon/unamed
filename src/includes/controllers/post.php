<?php

namespace Unamed\Controllers {
    class Post
    {
        protected $params = null;
        protected $target_post_type = 'post';

        public function __construct(array $params = array())
        {
            global $un;
            $this->params = $params;
            if (isset($_GET['post_type'])) {
                $this->target_post_type = $_GET['post_type'];
            }
            $un->enqueue('posts_selection', array($this, 'posts_selection'));
        }

        protected function post_selector()
        {
        }

        public function posts_selection()
        {
            global $un;
            $posts = array();
            $post_types = $un->getPostTypes();
            if ($post_types[$this->target_post_type]['public'] == true) {
                $posts = \Model::factory('Post')->
                    where('post_type', $this->target_post_type)->
                    order_by_desc('post_date')->
                    find_many();
                foreach ($posts as $post) {
                    $post->postmeta = $post->postmeta();
                }
            } else {
                $un->set404();
            }
            $un->setSelectedPosts($posts);

            return;
        }
    };
}
