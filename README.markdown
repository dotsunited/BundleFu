Du_BundleFu
===========

Du_BundleFu is PHP5.3+ library which bundles multiple css/javascript files into a big package and sends it out at once.

It is a port of the [Ruby on Rails](http://rubyonrails.org) plugin [bundle-fu](http://code.google.com/p/bundle-fu/).

In short, it turns this:

    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/jquery.myplugin.js"></script>
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/app.module.js"></script>
    <link media="screen" type="text/css" href="/css/reset.css">
    <link media="screen" type="text/css" href="/css/jquery.myplugin.css">
    <link media="screen" type="text/css" href="/css/app.css">
    <link media="screen" type="text/css" href="/css/app.module.css">

Into this:

    <link type="text/css" src="/js/cache/bundle_3f84da97fc873ca8371a8203fcdd8a82.css?1234567890"></script>
    <script type="text/javascript" src="/js/cache/bundle_3f84da97fc873ca8371a8203fcdd8a82.css?1234567890"></script>

## Integration ##

BundleFu offers integrations into the following frameworks/libraries:

* [CakePHP](http://github.com/dotsunited/du-bundlefu/tree/master/integration/cakephp/)
* [CodeIgniter](http://github.com/dotsunited/du-bundlefu/tree/master/integration/codeigniter/)
* [Lithium](http://github.com/dotsunited/du-bundlefu/tree/master/integration/lithium/)
* [Twig](http://github.com/dotsunited/du-bundlefu/tree/master/integration/twig/)
* [Zend Framework 1.x.x](http://github.com/dotsunited/du-bundlefu/tree/master/integration/zf1/)

## Installation ##

You can install Du_BundleFu via the [Dots United PEAR channel](http://pear.dotsunited.de). Run this from your command line:

    pear channel-discover pear.dotsunited.de
    pear install dotsunited/Du_BundleFu-beta

## Prerequisites ##

Du_BundleFu needs at least PHP 5.3.0 to run and requires that you have setup autoloading. Most modern frameworks
have tools to setup autoloading, if you are unsure you can use the following code snippet in your bootstrap file:

    spl_autoload_register(function($className) {
        if (strpos($className, 'Du\\BundleFu\\') === 0) {
            require str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
        }
    });

This requires, that you have installed Du_BundleFu in your `include_path` which is already the case if you have installed Du_BundleFu via PEAR.

## Usage ##

Configure a BundleFu instance:

    <?php
    $bundleFu = new \Du\BundleFu\BundleFu();

    $bundleFu
        // Set the document root
        ->setDocRoot('/path/to/your/document_root')

        // Set the css cache path (relative to the document root)
        ->setCssCachePath('css/cache')

        // Set the javascript cache path (relative to the document root)
        ->setJsCachePath('js/cache');
    ?>

Use the instance to bundle your files in your templates:

    <?php $bundleFu->start(); ?>
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/jquery.myplugin.js"></script>
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/app.module.js"></script>
    <link media="screen" type="text/css" href="/css/reset.css">
    <link media="screen" type="text/css" href="/css/jquery.myplugin.css">
    <link media="screen" type="text/css" href="/css/app.css">
    <link media="screen" type="text/css" href="/css/app.module.css">
    <?php $bundleFu->end(); ?>

Output the bundle `<script>` and `<link>` tags wherever you want:

    <?php 
    // Renders both <script> and <link> tags
    echo $bundleFu->render();

    // Renders the <link> tag only
    echo $bundleFu->renderCss();

    // Renders the <script> tag only
    echo $bundleFu->renderJs();
    ?>

## Filters ##

You can manipulate the bundled css/javascript code with filters. Filters are classes which implement the `\Du\BundleFu\Filter\Filter` interface.

You can add filters like that:

    $bundleFu->getCssFilterChain()->addFilter(new MyCssFilter());
    $bundleFu->getJsFilterChain()->addFilter(my MyJsFilter());

### Example ###

Du_BundleFu provides a filter to compile javascript code with the [Google Closure Compiler](http://code.google.com/intl/de-DE/closure/compiler/) using the [Service API](http://code.google.com/intl/de-DE/closure/compiler/docs/api-ref.html).

Simply add the `\Du\BundleFu\Filter\ClosureCompilerService` filter and your javascript bundles will be automatically compiled:

    $bundleFu->getJsFilterChain()->addFilter(new \Du\BundleFu\Filter\ClosureCompilerService());
