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
     * Option
     *
     * @category Model
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     * @since    1.0
     */
    class Option extends \Model
    {
        /* Properties */
        public static $_table = 'options'; // leading _ because of Paris
        public static $_id_column = 'option_id'; // leading _ because of Paris

        /* Methods */
        /**
         * __construct
         */
        public function __construct() 
        {
        }
    };
}
