<?php

namespace Unamed\Interfaces {
    interface Response
    {
        function addHeader($name, $value);
        function addHeaders(array $headers);
        function deliver($request, $data);
        function setOptions(array $options);
        function setStatus($status);
    };
}
