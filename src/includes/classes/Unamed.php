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
        public $plugins = array();
        protected $actions = array();
        protected $cache = null;
        protected $cacheKey = null;
        protected $fc = null;
        protected $isAdmin = null;
        protected $pageHookRunOrder = array(
            'init',
            'pluginsLoaded',
            'setupTheme',
            'dispatch',
            'postsSelection',
            'templateIncluded',
            'shutdown',
        );
        protected $runHooks = array();
        protected $post_types = array(
            'post' => array(
                'public' => true,
            ),
        );
        protected $scripts = null;
        protected $selectedPosts = null;
        protected $styles = null;
        protected $theme = null;
        protected $viewData = array();

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
            $dir = PLUGINS_DIR;
            if ($this->isAdmin) $dir = '../' . $dir;
            if ($d = dir($dir)) {
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
                        $this->plugins[] = $name;
                        if (class_exists($name))
                            $this->{$name} = new $name();
                    }
                }
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
         * getRouteData
         *
         * gets route data from front controller
         *
         * @return array
         */
        public function getRouteData()
        {
            return $this->fc->router->getData();
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
         * getViewData
         *
         * gets view data
         *
         * @return array 
         */
        public function getViewData()
        {
            return $this->viewData;
        }

        /**
         * setViewData
         *
         * sets view data
         *
         * @param array $data - data to be passed
         *
         * @return object(Unamed)
         */ 
        public function setViewData(array $data)
        {
            $this->viewData = $data;
            return $this;
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
            $styles = new \SplQueue();
            $overwrite = false;

            while (!$this->styles->isEmpty())
            {
                $style = $this->styles->dequeue();
                if ($style->handle === $handle) {
                    if ($src !== '')
                        $style->src = $src;
                    if ($deps !== array())
                        $style->deps = $deps;
                    if ($ver !== false)
                        $style->ver = $ver;
                    if ($media !== false)
                        $style->media = $media;
                    $style->enabled = false;
                    $overwrite = true;
                }
                $styles->enqueue($style);
            }

            if (!$overwrite)
            {
                $styles->enqueue(
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

            $this->styles = null;
            $this->styles = $styles;
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
            $styles = new \SplQueue();
            $overwrite = false;

            while (!$this->styles->isEmpty())
            {
                $style = $this->styles->dequeue();
                if ($style->handle === $handle) {
                    if ($src !== '')
                        $style->src = $src;
                    if ($deps !== array())
                        $style->deps = $deps;
                    if ($ver !== false)
                        $style->ver = $ver;
                    if ($media !== false)
                        $style->media = $media;
                    $style->enabled = true;
                    $overwrite = true;
                }
                $styles->enqueue($style);
            }

            if (!$overwrite)
            {
                $styles->enqueue(
                    new Style(
                        $handle,
                        $src,
                        $deps,
                        $ver,
                        $media,
                        true
                    )
                );
            }

            $this->styles = null;
            $this->styles = $styles;
        }

        /**
         * getStyles
         *
         * Gets enabled styles based on 
         *
         * @return SplQueue
         */
        public function getStyles()
        {
            $styles = new \SplQueue();
            $otherStyles = new \SplQueue();
            $arr = array();
            $order = array();

            while (!$this->styles->isEmpty())
            {
                $style = $this->styles->dequeue();

                if (!$style->enabled) 
                {
                    $otherStyles->enqueue($style);
                    continue;
                }

                $arr[$style->handle] = $style;
            }

            foreach ($arr as $handle => $style)
            {
                if (count($style->deps) === 0 && !in_array($handle, $order))
                {
                    array_unshift($order, $handle);
                    continue;
                }

                foreach ($style->deps as $dep)
                {
                    if (!in_array($dep, $order))
                        array_push($order, $dep);
                }

                $indexOfLastDep = 0;
                foreach ($style->deps as $dep)
                {
                    $iArr = array_keys($order, $dep);
                    $i = isset($iArr[count($iArr) - 1]) ? 
                        $iArr[count($iArr) - 1] : 0;
                    if ($i > $indexOfLastDep)
                        $indexOfLastDep = $i;
                }

                if (count($order) > $indexOfLastDep + 1)
                    $index = $indexOfLastDep + 1;
                else
                    $index = count($order);

                $before = array_slice($order, 0, $index);
                $after = array_slice($order, $index);
                if (!in_array($handle, $order))
                    $order = array_merge($before, array($handle), $after);
            }

            foreach ($order as $handle)
            {
                $styles->enqueue($arr[$handle]);
            }

            $this->styles = null;
            $this->styles = $otherStyles;
            return $styles;
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
            $scripts = new \SplQueue();
            $overwrite = false;

            while (!$this->scripts->isEmpty())
            {
                $script = $this->scripts->dequeue();
                if ($script->handle === $handle) {
                    if ($src !== '')
                        $script->src = $src;
                    if ($deps !== array())
                        $script->deps = $deps;
                    if ($ver !== false)
                        $script->ver = $ver;
                    if ($inFooter !== false)
                        $script->inFooter = $inFooter;
                    $script->enabled = false;
                    $overwrite = true;
                }
                $scripts->enqueue($script);
            }

            if (!$overwrite)
            {
                $scripts->enqueue(
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

            $this->scripts = null;
            $this->scripts = $scripts;
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
            $scripts = new \SplQueue();
            $overwrite = false;

            while (!$this->scripts->isEmpty())
            {
                $script = $this->scripts->dequeue();
                if ($script->handle === $handle) {
                    if ($src !== '')
                        $script->src = $src;
                    if ($deps !== array())
                        $script->deps = $deps;
                    if ($ver !== false)
                        $script->ver = $ver;
                    if ($inFooter !== false)
                        $script->inFooter = $inFooter;
                    $script->enabled = true;
                    $overwrite = true;
                }
                $scripts->enqueue($script);
            }

            if (!$overwrite)
            {
                $scripts->enqueue(
                    new Script(
                        $handle,
                        $src,
                        $deps,
                        $ver,
                        $inFooter,
                        true
                    )
                );
            }

            $this->scripts = null;
            $this->scripts = $scripts;
        }

        /**
         * getScripts
         *
         * Gets enabled scripts based on 
         *
         * @param bool $inFooter - default true
         *
         * @return SplQueue
         */
        public function getScripts($inFooter = true)
        {
            $scripts = new \SplQueue();
            $otherScripts = new \SplQueue();
            $arr = array();
            $order = array();

            while (!$this->scripts->isEmpty())
            {
                $script = $this->scripts->dequeue();

                if (!$script->enabled) 
                {
                    $otherScripts->enqueue($script);
                    continue;
                }

                if ($script->inFooter === $inFooter) 
                    $arr[$script->handle] = $script;
                else
                    $otherScripts->enqueue($script);
            }

            foreach ($arr as $handle => $script)
            {
                if (count($script->deps) === 0 && !in_array($handle, $order))
                {
                    array_unshift($order, $handle);
                    continue;
                }

                foreach ($script->deps as $dep)
                {
                    if (!in_array($dep, $order))
                        array_push($order, $dep);
                }

                $indexOfLastDep = 0;
                foreach ($script->deps as $dep)
                {
                    $iArr = array_keys($order, $dep);
                    $i = isset($iArr[count($iArr) - 1]) ? 
                        $iArr[count($iArr) - 1] : 0;
                    if ($i > $indexOfLastDep)
                        $indexOfLastDep = $i;
                }

                if (count($order) > $indexOfLastDep + 1)
                    $index = $indexOfLastDep + 1;
                else
                    $index = count($order);

                $before = array_slice($order, 0, $index);
                $after = array_slice($order, $index);
                if (!in_array($handle, $order))
                    $order = array_merge($before, array($handle), $after);
            }

            foreach ($order as $handle)
            {
                $scripts->enqueue($arr[$handle]);
            }

            $this->scripts = null;
            $this->scripts = $otherScripts;
            return $scripts;
        }

        /**
         * getFlashMessages
         *
         * Gets any flash messages/alerts to be presented to user
         *
         * returns array
         */
        public function getFlashMessages()
        {
            $messages = array();
            if (isset($_SESSION['unamed:flash_messages'])) {
                $messages = unserialize($_SESSION['unamed:flash_messages']);
            }
            $_SESSION['unamed:flash_messages'] = null;
            return $messages;
        }

        /**
         * addFlashMessage
         *
         * Adds a flash messages/alerts to be presented to user
         *
         * @param array $message - needs 'type' and 'message'
         *
         * returns object(Unamed)
         */
        public function addFlashMessage(array $message)
        {
            $messages = array();
            if (isset($_SESSION['unamed:flash_messages'])) {
                $messages = unserialize($_SESSION['unamed:flash_messages']);
            }
            if (isset($message['type']) && isset($message['message'])) {
                $messages[] = $message;
            }
            $_SESSION['unamed:flash_messages'] = serialize($messages);
            return $this;
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
            $this->execute('postInit');
            $this->runHooks[] = 'init';
            return;
        }

        /**
         * pluginsLoaded
         *
         * @return nothing
         */
        public function pluginsLoaded()
        {
            $this->execute('prePluginsLoaded');
            $this->loadPlugins();
            $this->execute('postPluginsLoaded');
            $this->runHooks[] = 'pluginsLoaded';
            return;
        }

        /**
         * setupTheme
         *
         * @return nothing
         */
        protected function setupTheme()
        {
            $this->execute('preSetupTheme');
            if (!$this->isAdmin) {
                $this->theme = new \stdClass();
                $this->theme->dir = THEMES_DIR . $this->options->theme . DS;
                $this->theme->has = $this->templateFiles();
                if ($this->theme->has['functions']) {
                    include_once $this->theme->dir . 'functions.php';
                }
            }
            $this->execute('postSetupTheme');
            $this->runHooks[] = 'setupTheme';
            return;
        }

        /**
         * dispatch
         *
         * @return nothing
         */
        protected function dispatch()
        {
            $this->execute('preDispatch');
            $this->fc->dispatch();
            $this->execute('postDispatch');
            $this->runHooks[] = 'dispatch';
            return;
        }

        /**
         * templateIncluded
         *
         * @return nothing
         */
        protected function templateIncluded()
        {
            $this->execute('preTemplateIncluded');
            if (!$this->isAdmin) {
                if ($this->theme->has['homepage'] && $this->isHome()) {
                    include_once $this->theme->dir . 'homepage.php';
                }
            } else {
                include_once BASE_DIR . ADMIN_DIR . 'templates/admin.php';
            }
            $this->execute('postTemplateIncluded');
            $this->runHooks[] = 'templateIncluded';
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
            $this->execute('preDeliver');
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
            $this->execute('postDeliver');
            $this->runHooks[] = 'deliver';
            return;
        }
    };
}
