CakePHP Integration
===================

bundle_fu.php is a [CakePHP](http://cakephp.org) view helper integrating Du_BundeFu into your CakePHP applications.

## Installation ##

Place bundle_fu.php in app/views/helpers and load the helper inside your controllers:

    <?php
    class MyController extends AppController {
        var $helpers = array('BundleFu');
    }
    ?>

For more information about helpers, see the [CakePHP Docs](http://book.cakephp.org/view/99/Using-Helpers).

## Usage ##

Use the helper in your views:

    <?php $bundleFu()->start(); ?>
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/jquery.myplugin.js"></script>
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/app.module.js"></script>
    <link media="screen" type="text/css" href="/css/reset.css">
    <link media="screen" type="text/css" href="/css/jquery.myplugin.css">
    <link media="screen" type="text/css" href="/css/app.css">
    <link media="screen" type="text/css" href="/css/app.module.css">
    <?php $bundleFu()->end(); ?>

    <?php echo $bundleFu(); ?>
