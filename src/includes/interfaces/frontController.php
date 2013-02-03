<?php

namespace Unamed\Interfaces {
	interface FrontController {
	    function deliver($data);
	    function dispatch();
	};
}