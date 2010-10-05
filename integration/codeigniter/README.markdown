CodeIgniter Integration
=======================

bundle_fu_helper.php is a [CodeIgniter](http://codeigniter.com) helper integrating Du_BundeFu into your CodeIgniter applications.

## Installation ##

Place bundle_fu_helper.php in system/application/helpers and load the helper inside your controllers:

    $this->load->helper('bundle_fu');

Alternatively you can autoload it during system initialization by adding it to application/config/autoload.php:

    $autoload['helper'] = array('bundle_fu');

For more information about helpers, see the [CodeIgniter Docs](http://codeigniter.com/user_guide/general/helpers.html).

## Usage ##

Use the helper in your views:

    <?php bundle_fu()->start(); ?>
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="/js/jquery.myplugin.js"></script>
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/js/app.module.js"></script>
    <link media="screen" type="text/css" href="/css/reset.css">
    <link media="screen" type="text/css" href="/css/jquery.myplugin.css">
    <link media="screen" type="text/css" href="/css/app.css">
    <link media="screen" type="text/css" href="/css/app.module.css">
    <?php bundle_fu()->end(); ?>

    <?php echo bundle_fu()->render(); ?>
