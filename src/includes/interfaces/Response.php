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
     * Response
     *
     * @category Class
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     * @since    1.0
     */
    interface Response
    {
        /**
         * addHeader
         *
         * @param string $name    - 
         * @param string $value   - 
         * @param bool   $replace - 
         *
         * @return object(Response)
         */
        function addHeader($name, $value, $replace);

        /**
         * addHeaders
         *
         * @param bool $headers - 
         *
         * @return object(Response)
         */
        function addHeaders(array $headers);

        /**
         * deliver
         *
         * @param string          $request - 
         * @param callback|string $data    - 
         *
         * @return object(Response)
         */
        function deliver($request, $data);

        /**
         * noCache
         *
         * @return object(Response)
         */
        function noCache();

        /**
         * setOptions
         *
         * @param string $options - 
         *
         * @return object(Response)
         */
        function setOptions(array $options);

        /**
         * setStatus
         *
         * @param int $status - 
         *
         * @return object(Response)
         */
        function setStatus($status);

        /**
         * useHttp10
         *
         * @return object(Response)
         */
        function useHttp10();

        /**
         * useHttp11
         *
         * @return object(Response)
         */
        function useHttp11();
    };
}
