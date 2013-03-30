<?php

class option extends Model
{
    public static $_table = 'options';
    public static $_id_column = 'option_id';

    public function __construct() {}
    public function Option() {return $this->__construct();}

};
