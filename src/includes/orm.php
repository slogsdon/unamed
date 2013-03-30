<?php

ORM::configure('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME);
ORM::configure('username', DB_USER);
ORM::configure('password', DB_PASS);
ORM::configure('logging', DB_LOGGING);

foreach (glob(MODELS_DIR . '*.php') as $filename) {
    include_once $filename;
}
