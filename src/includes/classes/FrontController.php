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

namespace Unamed\FrontController;
use Unamed\Interfaces;
{
    /**
     * FrontController implementations
     *
     * these methods mainly act as helper functions
     *
     * @category FrontController
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     * @since    1.0
     */
    class FrontController implements Interfaces\FrontController
    {
        /* Properties */
        public $dispatcher = null;
        public $request = null;
        public $response = null;
        public $router = null;

        /* Methods */
        /**
         * __construct
         *
         * TODO: this is apparently not done. $options doesn't go anywhere
         *
         * @param array $options - (opt.)
         */
        public function __construct(array $options = array())
        {
            $this->request = new Request();
            $this->response = new Response();
            $this->router = new Router($this->request);
        }

        /**
         * deliver
         *
         * @param callback|string $data - what to output
         *
         * @return nothing
         */
        public function deliver($data)
        {
            $this->response->deliver($this->request, $data);
            return;
        }

        /**
         * dispatch
         * 
         * send control over to correct controller
         *
         * @return nothing
         */
        public function dispatch()
        {
            $data = $this->router->getData();;
            $c = $data->controller;
            $ref = new $c($data->params);
            $a = $data->action;
            if (!is_null($a) && !empty($a)) call_user_func(array($ref, $a));
            return;
        }
    };
}
