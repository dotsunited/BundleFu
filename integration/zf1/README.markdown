Zend Framework 1.x.x Integration
================================

BundleFu.php is a Zend Framework 1.x.x view helper integrating Du_BundeFu into your Zend Framework applications.

## Installation ##

Place BundleFu.php somewhere in your application structure and initialize it in your bootstrap class:

    <?php

    class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
    {
        protected function _initBundleFu()
        {
            $this->bootstrap('view');
            $view = $this->getResource('view');

            $view->addHelperPath(
                '/path/where/you/stored/the/class'
            );

            $view->getHelper('BundleFu')
                ->setDocRoot(APPLICATION_PATH . '/../public')
                // Relative to the doc root
                ->setCssCachePath('css/cache')
                ->setJsCachePath('js/cache');
        }
    }

## Usage ##

Use the helper in your views:

    <?php $this->bundleFu()->start(); ?>
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/jquery.myplugin.js"></script>
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/app.module.js"></script>
    <link media="screen" type="text/css" href="/css/reset.css">
    <link media="screen" type="text/css" href="/css/jquery.myplugin.css">
    <link media="screen" type="text/css" href="/css/app.css">
    <link media="screen" type="text/css" href="/css/app.module.css">
    <?php $this->bundleFu()->end(); ?>

    <?php echo $this->bundleFu(); ?>

If you're using the HeadLink and HeadScript helpers, simply let BundleFu bundle files registered with it:

    <?php 
    echo $this->bundleFu()
           ->bundleHeadLink($this->headLink())
           ->bundleHeadScript($this->headScript());

    // Render remaining inline scripts etc.
    echo $this->headLink();
    echo $this->headScript();
    ?>
