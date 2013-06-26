<?php
/**
 * Unamed - a WordPress replacement
 *
 * @category CMS
 * @package  Unamed
 * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
 * @license  MIT http://mit.edu/
 * @link     http://bitbucket.org/slogsdon/unamed
 */

namespace Unamed\Controllers {
    /**
     * Post
     *
     * @category Controller
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     * @since    1.0
     */
    class Post
    {
        /* Properties */
        protected $params = null;
        protected $target_post_type = 'post';

        /* Methods */
        /**
         * __construct
         *
         * @param array $params - passed from router
         */
        public function __construct(array $params = array())
        {
            global $un;
            $this->params = $params;
            if (isset($_GET['post_type'])) {
                $this->target_post_type = $_GET['post_type'];
            }
            $un->enqueue('postsSelection', array($this, 'postsSelection'));
        }

        /**
         * postSelector
         *
         * TODO: remember wtf this was for
         *
         * @return nothing
         */
        protected function postSelector()
        {
        }

        /**
         * postsSelection
         *
         * @return nothing
         */
        public function postsSelection()
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
