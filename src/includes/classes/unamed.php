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
     * Unamed base class
     *
     * This dude is like, the team lead. He makes sure
     * everyone else gets their shit done.
     *
     * @category Class
     * @package  Unamed
     * @author   Shane Logsdon <shane.a.logsdon@gmail.com>
     * @license  MIT http://mit.edu/
     * @link     http://bitbucket.org/slogsdon/unamed
     * @since    1.0
     */
    class Unamed
    {
        /* Properties */
        public $options = null;
        protected $actions = array();
        protected $cache = null;
        protected $cacheKey = null;
        protected $fc = null;
        protected $isAdmin = null;
        protected $pageHookRunOrder = array(
            'startup',
            'pluginsLoaded',
            'setup_theme',
            'dispatch',
            'postsSelection',
            'templateIncluded',
            'shutdown',
        );
        protected $post_types = array(
            'post' => array(
                'public' => true,
            ),
        );
        protected $scripts = null;
        protected $selectedPosts = null;
        protected $styles = null;
        protected $theme = null;

        /* Methods */
        /**
         * __construct
         *
         * @param bool $isAdmin - is the session for the admin?
         */
        public function __construct($isAdmin = false)
        {
            $this->isAdmin = $isAdmin;
            $this->fc = new FrontController\FrontController();
            $this->createCacheKey();
            if (!$this->isAdmin
                && ENABLE_CACHE
                && ENABLE_PAGE_CACHE
                && class_exists('Cache')
                && Cache::isHit($this->cacheKey)
            ) {
                $this->fc->response->addHeader('X-Cached', 'true');
                $this->fc->deliver(Cache::get($this->cacheKey));
            } else {
                $this->startSession();
                $this->loadOptions();
                foreach ($this->pageHookRunOrder as $hook) {
                    if (is_callable(array($this, $hook))) {
                        $this->enqueue($hook, array($this, $hook));
                    }
                }
            }

            return;
        }

        /**
         * isAdmin
         *
         * @return object(Unamed)
         */
        public function isAdmin()
        {
            return $this->isAdmin;
        }

        /**
         * addRoute
         *
         * TODO: decide if it's necessary to keep this public
         *
         * @param string $route      - route to be compiled to regex
         * @param string $controller - controller class name
         *
         * @return object(Unamed)
         */
        public function addRoute($route, $controller)
        {
            $this->fc->router->addRoute($route, $controller);
            return $this;
        }

        /**
         * addRoutes
         *
         * allows for multiple routes to be added at once
         *
         * @param array $routes - key,value pairs of routes,controllers
         *
         * @return object(Unamed)
         */
        public function addRoutes(array $routes)
        {
            $this->fc->router->addRoutes($routes);
            return $this;
        }

        /**
         * startSession
         *
         * @return object(Unamed)
         */
        protected function startSession()
        {
            if (!session_id()) {
                session_start();
            }
            return $this;
        }

        /**
         * run
         *
         * runs all hooks in order with all actions associated with each
         *
         * @return nothing
         */
        public function run()
        {
            foreach ($this->pageHookRunOrder as $hook) {
                $this->execute($hook);
            }
            return;
        }

        /**
         * enqueue
         *
         * @param string   $hook   - name of hook
         * @param callback $action - action to be ran
         *
         * @return object(Unamed)
         */
        public function enqueue($hook, $action)
        {
            $this->actions[$hook][] = $action;
            return $this;
        }

        /**
         * execute
         *
         * @param string $hook - name of hook
         *
         * @return object(Unamed)
         */
        public function execute($hook)
        {
            if (array_key_exists($hook, $this->actions)) {
                foreach ($this->actions[$hook] as $action) {
                    if (is_callable($action)) {
                        call_user_func($action);
                    }
                }
            }
            return $this;
        }

        /**
         * loadPlugins
         *
         * old code that's too complex. loads the plugin folder.
         * TODO: find a better solution
         *
         * @return object(Unamed)
         */
        protected function loadPlugins()
        {
            // read $dir, add filenames
            $dir = 'plugins';
            if ($this->isAdmin) $dir = '../' . $dir;
            if ($d = dir($dir . DS)) {
                $dir .= DS;
                $a = array();
                while (false !== ($file = $d->read())) {
                    if ($file != "."
                        && $file != ".."
                        && !in_array($file, $a)
                    ) {
                        if (substr($file, -4) == ".php") {
                            $a[ucfirst(substr($file, 0, -4))] = $file;
                        } elseif (is_dir($dir . $file)) {
                            $a[ucfirst($file)] = $file . DS . $file . '.php';
                        }
                    }
                }
                // include and create
                foreach ($a as $name => $file) {
                    if (file_exists($dir . $file)) {
                        include_once $dir . $file;
                    }
                }
            } elseif (file_exists($dir . '.php')) {
                include_once $dir . '.php';
            }
            return $this;
        }

        /**
         * createCacheKey
         *
         * @return object(Unamed)
         */
        protected function createCacheKey()
        {
            $this->cacheKey = 'un_cache' . str_replace('/', '_', $_SERVER['REQUEST_URI']);
            return $this;
        }

        /**
         * loadOptions
         *
         * grabs 'autoload' options from db
         *
         * @return object(Unamed)
         */
        protected function loadOptions()
        {
            if (is_null($this->options)) {
                $this->options = new \stdClass();
                $options = \Model::factory('Option')->where('autoload', '1')->find_many();
                foreach ($options as $option) {
                    $name = $option->option_name;
                    $this->options->$name = $option->option_value;
                }
            }
            return $this;
        }

        /**
         * getOption
         *
         * grabs a specific option value from db
         *
         * @param string $name - option name
         *
         * @return string
         */
        public function getOption($name)
        {
            $ret = null;
            if (!is_null($this->options->$name) && !empty($this->options->$name)) {
                $ret = $this->options->$name;
            } else {
                $option = \Model::factory('Option')->where('option_name', $name)->find_one();
                $ret = $option->option_value;
            }
            return $ret;
        }

        /**
         * templateFiles
         *
         * checks existance of specific template files and a template file for
         * each registered post_type
         *
         * @return array(string=>bool)
         */
        protected function templateFiles()
        {
            $return = array(
                'homepage' => file_exists($this->theme->dir . 'homepage.php'),
                'functions'=> file_exists($this->theme->dir . 'functions.php')
            );
            foreach ($this->post_types as $post_type => $postTypeOptions) {
                if ($postTypeOptions['public'] === true)
                    $ret[$post_type] = file_exists(
                        $this->theme->dir . $post_type . '.php'
                    );
            }
            return $return;
        }

        /**
         * isHome
         *
         * @return bool
         */
        public function isHome()
        {
            return true;
        }

        /**
         * is404
         *
         * @return bool
         */
        public function is404()
        {
            return false;
        }

        /**
         * set404
         *
         * @return object(Unamed)
         */
        public function set404()
        {
            $this->fc->response->setStatus(404);
            return $this;
        }

        /**
         * setSeletedPosts
         *
         * @param array(Post) $posts - store the posts
         *
         * @return object(Unamed)
         */
        public function setSelectedPosts($posts)
        {
            $this->selectedPosts = $posts;
            return $this;
        }

        /**
         * getPostTypes
         *
         * TODO: figure out in what format these should be stored/returned
         *
         * @return array(array)
         */
        public function getPostTypes()
        {
            return $this->post_types;
        }

        /**
         * thePosts
         *
         * @return array(Post)
         */
        public function thePosts()
        {
            return $this->selectedPosts;
        }

        /**
         * hasPosts
         *
         * @return bool
         */
        public function hasPosts()
        {
            return !is_null($this->selectedPosts) 
                && count($this->selectedPosts);
        }

        /**
         * registerStyle
         *
         * @param string        $handle - label for the stylesheet
         * @param string        $src    - location of the file (opt.)
         * @param array(string) $deps   - dependencies to be loaded (opt.)
         * @param string        $ver    - version (opt.)
         * @param string        $media  - (opt.)
         *
         * @return nothing
         */
        public function registerStyle(
            $handle, 
            $src = '', 
            $deps = array(), 
            $ver = false, 
            $media = false
        ) {
            $this->styles->enqueue(
                new Style(
                    $handle,
                    $src,
                    $deps,
                    $ver,
                    $media,
                    false
                )
            );
        }

        /**
         * enqueueStyle
         *
         * @param string        $handle - label for the stylesheet
         * @param string        $src    - location of the file (opt.)
         * @param array(string) $deps   - dependencies to be loaded (opt.)
         * @param string        $ver    - version (opt.)
         * @param string        $media  - (opt.)
         *
         * @return nothing
         */
        public function enqueueStyle(
            $handle, 
            $src = '', 
            $deps = array(), 
            $ver = false, 
            $media = false
        ) {
        }

        /**
         * registerScript
         *
         * @param string        $handle   - label for the script
         * @param string        $src      - location of the file (opt.)
         * @param array(string) $deps     - dependencies to be loaded (opt.)
         * @param string        $ver      - version (opt.)
         * @param string        $inFooter - load in the footer (opt.)
         *
         * @return nothing
         */
        public function registerScript(
            $handle, 
            $src = '', 
            $deps = array(), 
            $ver = false, 
            $inFooter = true
        ) {
            $this->scripts->enqueue(
                new Script(
                    $handle,
                    $src,
                    $deps,
                    $ver,
                    $inFooter,
                    false
                )
            );
        }

        /**
         * enqueueScript
         *
         * @param string        $handle   - label for the stylesheet
         * @param string        $src      - location of the file (opt.)
         * @param array(string) $deps     - dependencies to be loaded (opt.)
         * @param string        $ver      - version (opt.)
         * @param string        $inFooter - load in the footer (opt.)
         *
         * @return nothing
         */
        public function enqueueScript(
            $handle, 
            $src = '', 
            $deps = array(), 
            $ver = false, 
            $inFooter = true
        ) {
        }

        // Page Hook Methods
        /**
         * init
         *
         * let's get this party started
         *
         * @return nothing
         */
        protected function init()
        {
            ob_start();
            $this->scripts = new \SplQueue();
            $this->styles = new \SplQueue();
            $this->execute('post_init');
            return;
        }

        /**
         * pluginsLoaded
         *
         * @return nothing
         */
        protected function pluginsLoaded()
        {
            $this->execute('pre_plugins_loaded');
            $this->loadPlugins();
            $this->execute('post_plugins_loaded');
            return;
        }

        /**
         * setupTheme
         *
         * @return nothing
         */
        protected function setupTheme()
        {
            $this->execute('pre_setup_theme');
            if (!$this->isAdmin) {
                $this->theme = new \stdClass();
                $this->theme->dir = THEMES_DIR . $this->options->theme . DS;
                $this->theme->has = $this->templateFiles();
                if ($this->theme->has['functions']) {
                    include_once $this->theme->dir . 'functions.php';
                }
            }
            $this->execute('post_setup_theme');
            return;
        }

        /**
         * dispatch
         *
         * @return nothing
         */
        protected function dispatch()
        {
            $this->execute('pre_dispatch');
            $this->fc->dispatch();
            $this->execute('post_dispatch');
            return;
        }

        /**
         * templateIncluded
         *
         * @return nothing
         */
        protected function templateIncluded()
        {
            $this->execute('pre_template_included');
            if (!$this->isAdmin) {
                if ($this->theme->has['homepage'] && $this->is_home()) {
                    include_once $this->theme->dir . 'homepage.php';
                }
            } else {
                include_once INCLUDES_DIR . 'frontend.php';
            }
            $this->execute('post_template_included');
            return;
        }

        /**
         * deliver
         *
         * this is the reason all we did all this
         *
         * @return nothing
         */
        protected function deliver()
        {
            $this->execute('pre_deliver');
            $buffer = ob_get_clean();
            $config = array(
                'indent' => true,
                'input-xml' => true,
                'wrap' => 200
            );
            $tidy = tidy_repair_string($buffer, $config, 'UTF8');
            if (!$this->isAdmin
                && !$this->is_404()
                && ENABLE_CACHE
                && ENABLE_PAGE_CACHE
                && class_exists('Cache')
            ) {
                Cache::add($this->cacheKey, (string) $tidy);
            }
            $this->fc->deliver($tidy);
            $this->execute('post_deliver');
            return;
        }
    };
}
