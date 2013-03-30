<?php

namespace Unamed\Controllers {
    class Api
    {
        protected $params = null;

        public function __construct(array $params = array())
        {
            global $un;
            $this->params = $params;
            die('in api controller');
        }
    };
}
