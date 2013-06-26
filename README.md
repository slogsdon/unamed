# Unamed
---
[![Build Status](https://travis-ci.org/slogsdon/unamed.png)](https://travis-ci.org/slogsdon/unamed)

What is it?
-----

Unamed is a [Wordpress](http://www.wordpress.org/) replacement built with simplicity and speed in mind.  With that in mind, Unamed allows for web-site owners to use Wordpress plugins and themes without issue (read: it just works).

Why? Wordpress works.
-----

To be honest, I wanted to learn more about Wordpress and all it has to offer, and I thought this would be a nice way of doing such a thing. I started working on the initial CMS years ago and only recently decided to add in Wordpress support as a plugin (so it can be removed if desired).

What's done?
-----
* It runs
* It speaks SQL (via [Paris](https://github.com/j4mie/paris))
* It serves from a full-page cache, if you're into that sort of thing

What's in progress?
-----

* It can make things pretty
* Admin interface has a great start
* It allows for hooks (similar to [these](http://codex.wordpress.org/Plugin_API)) to be used

What's left?
-----

* Verification that all Wordpress hooks work appropriately
* Write phpunit tests

Setup (with [Composer](http://getcomposer.org/))
-----

    # Clone, and run composer's install

    git clone https://github.com/slogsdon/unamed.git
    cd unamed
    curl -s https://getcomposer.org/installer | php
    php composer.phar install

    # Point your webserver to the directory
    # Enjoy

Dependencies (if you want to install without composer)
-----

* [Paris](https://github.com/j4mie/paris)
