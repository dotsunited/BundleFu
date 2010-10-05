Twig Integration
================

BundleFu.php is a [Twig](http://www.twig-project.org) extension integrating Du_BundeFu into your applications using the Twig template language.

## Installation ##

Include BundleFu.php and add Twig_Extension_BundleFu as an extension to Twig:

    <?php

    include_once '/path/to/BundleFu.php';

    $extension = new Twig_Extension_BundleFu();
    $extension->setDocRoot('/path/to/docroot');
    $twig->addExtension($extension);

## Usage ##

Use the extension in your templates:

    {% bundlefu start() %}
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/jquery.myplugin.js"></script>
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/app.module.js"></script>
    <link media="screen" type="text/css" href="/css/reset.css">
    <link media="screen" type="text/css" href="/css/jquery.myplugin.css">
    <link media="screen" type="text/css" href="/css/app.css">
    <link media="screen" type="text/css" href="/css/app.module.css">
    {% bundlefu end() %}

    {% bundlefu render() %}
