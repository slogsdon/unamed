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
     * Api
     *
     * @category Controller
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     * @since    1.0
     */
    class Api
    {
        /* Properties */
        protected $params = null;

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
            die('in api controller');
        }
    };
}
