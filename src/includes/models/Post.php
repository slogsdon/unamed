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

namespace Unamed\Models {
    /**
     * Post
     *
     * @category Model
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     * @since    1.0
     */
    class Post extends \Model
    {
        /* Properties */
        public static $_table = 'posts'; // leading _ because of Paris
        public static $_id_column = 'ID'; // leading _ because of Paris

        /* Methods */
        /**
         * __construct
         */
        public function __construct() 
        {
        }

        /**
         * postmeta
         *
         * links meta table to posts table
         *
         * @return object(ORM)
         */
        public function postmeta()
        {
            return $this->has_many('Postmeta', 'post_id')->find_many();
        }

        /**
         * UpdateWith
         *
         * Updates the object's data values from an array
         *
         * @param array $data - the data to update
         *
         * @return object(ORM)
         */
        public function UpdateWith(array $data)
        {
            foreach (array_keys($data) as $key => $value)
            {
                $obj = $this->as_array();
                if (array_key_exists($key, $obj))
                    $this->{$key} = $value;
            }
            return $this;
        }
    };
}
