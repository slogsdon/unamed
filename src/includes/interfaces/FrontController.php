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
     * FrontController
     *
     * these methods mainly act as helper functions
     *
     * @category Interface
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     * @since    1.0
     */
    interface FrontController
    {
        /**
         * deliver
         *
         * @param callback|string $data - what to output
         *
         * @return nothing
         */
        function deliver($data);

        /**
         * dispatch
         * 
         * send control over to correct controller
         *
         * @return nothing
         */
        function dispatch();
    };
}
