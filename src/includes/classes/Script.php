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
     * Style base class
     *
     * @category Class
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     * @since    1.0
     */
    class Script
    {
        public $handle = null;
        public $src = null;
        public $deps = null;
        public $ver = null;
        public $inFooter = null;
        public $enabled = null;

        /**
         * __construct
         *
         * @param string        $handle   - label for the stylesheet
         * @param string        $src      - location of the file
         * @param array(string) $deps     - dependencies to be loaded
         * @param string        $ver      - version
         * @param string        $inFooter - 
         * @param bool          $enable   - 
         *
         * @return nothing
         */
        public function __construct(
            $handle, 
            $src, 
            $deps,
            $ver, 
            $inFooter, 
            $enabled
        ) {
            $this->handle = $handle;
            $this->src = $src;
            $this->deps = $deps;
            $this->ver = $ver;
            $this->inFooter = $inFooter;
            $this->enabled = $enabled;
        }
    };
}
