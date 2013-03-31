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

namespace Unamed\Interfaces {
    /**
     * Request
     *
     * @category Interface
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     * @since    1.0
     */
    interface Request
    {
        /**
         * body
         *
         * @return string
         */
        function body();

        /**
         * headers
         *
         * @return array
         */
        function headers();

        /**
         * method
         *
         * @return string
         */
        function method();

        /**
         * uri
         *
         * @return string
         */
        function uri();
    };
}
