Lithium Integration
===================

BundleFu.php is a [Lithium](http://lithify.me) view helper integrating Du_BundeFu into your Lithium applications.

## Installation ##

Place BundleFu.php in app/extensions/helper.

## Usage ##

Use the helper in your views:

    <?php $this->bundleFu->start(); ?>
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/jquery.myplugin.js"></script>
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/app.module.js"></script>
    <link media="screen" type="text/css" href="/css/reset.css">
    <link media="screen" type="text/css" href="/css/jquery.myplugin.css">
    <link media="screen" type="text/css" href="/css/app.css">
    <link media="screen" type="text/css" href="/css/app.module.css">
    <?php $this->bundleFu->end(); ?>

    <?php echo $this->bundleFu; ?>
