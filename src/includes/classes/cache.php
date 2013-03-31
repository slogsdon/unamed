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

namespace Unamed {
    /**
     * Cache
     *
     * simple file-based implementation. meant to serve as baseline
     *
     * @category Class
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     */
    class Cache
    {
        /**
         * add
         *
         * @param string $key  - identifier
         * @param string $data - content
         *
         * @return bool
         */
        public static function add($key, $data)
        {
            $file = md5($key);
            $path = substr($file, 0, 1) . DS . substr($file, 0, 2) . DS;
            if (!is_dir($path) && !file_exists($path)) 
                mkdir(CACHE_DIR . $path, 0777, true);
            return file_put_contents(CACHE_DIR . $path . $file, $data);
        }

        /**
         * get
         *
         * @param string $key - identifier
         *
         * @return string
         */
        public static function get($key)
        {
            $file = md5($key);
            $path = substr($file, 0, 1) . DS . substr($file, 0, 2) . DS;
            return file_get_contents(CACHE_DIR . $path . $file);
        }

        /**
         * isHit
         *
         * @param string $key - identifier
         *
         * @return bool
         */
        public static function isHit($key)
        {
            $file = md5($key);
            $path = substr($file, 0, 1) . DS . substr($file, 0, 2) . DS;
            return file_exists(CACHE_DIR . $path . $file);
        }
    };
}
