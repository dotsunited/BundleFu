BundleFu
========

[![Build Status](https://travis-ci.org/dotsunited/BundleFu.svg?branch=master)](http://travis-ci.org/dotsunited/BundleFu)

BundleFu is a PHP 5.3+ library which bundles multiple css/javascript files into a big package and sends it out at once.

It is highly inspired by the [Ruby on Rails](http://rubyonrails.org) plugin [bundle-fu](http://code.google.com/p/bundle-fu/).

In short, it turns this:

```html
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.myplugin.js"></script>
<script type="text/javascript" src="/js/app.js"></script>
<script type="text/javascript" src="/js/app.module.js"></script>
<link media="screen" type="text/css" href="/css/reset.css">
<link media="screen" type="text/css" href="/css/jquery.myplugin.css">
<link media="screen" type="text/css" href="/css/app.css">
<link media="screen" type="text/css" href="/css/app.module.css">
```

Into this:

```html
<link href="/css/cache/bundle_3f84da97fc873ca8371a8203fcdd8a82.css?1234567890" rel="stylesheet" type="text/css">
<script src="/js/cache/bundle_3f84da97fc873ca8371a8203fcdd8a82.js?1234567890" type="text/javascript"></script>
```

Features
--------

  * Automatically detects modifications to your css and javascript files and regenerates the bundles automatically.
  * Bundle contents can be modified by filters for css url rewriting to avoid broken images, code minification and compression etc. (A [Google Closure Compiler](http://code.google.com/closure/compiler/) filter using the [Service API](http://code.google.com/closure/compiler/docs/api-ref.html) comes with the library).

Installation
------------

BundleFu can be installed using the [Composer](http://packagist.org) tool. You can either add `dotsunited/bundlefu` to the dependencies in your composer.json, or if you want to install BundleFu as standalone, go to the main directory and run:

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
```

You can then use the composer-generated autoloader to access the BundleFu classes:

```php
<?php
require 'vendor/autoload.php';
?>
```

Usage
-----

Configure a Bundle instance:

```php
<?php
$bundle = new \DotsUnited\BundleFu\Bundle();

$bundle
    // Set the document root
    ->setDocRoot('/path/to/your/document_root')

    // Set the css cache path (relative to the document root)
    ->setCssCachePath('css/cache')

    // Set the javascript cache path (relative to the document root)
    ->setJsCachePath('js/cache');
?>
```

Alternatively, you can pass an options array to the constructor (or use the method `setOptions` later):

```php
<?php
$options = array(
    'doc_root' => '/path/to/your/document_root',
    'css_cache_path' => 'css/cache',
    'js_cache_path' => 'js/cache',
);

$bundle = new \DotsUnited\BundleFu\Bundle($options);
?>
```

Use the instance to bundle your files in your templates:

```php
<?php $bundle->start(); ?>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.myplugin.js"></script>
<script type="text/javascript" src="/js/app.js"></script>
<script type="text/javascript" src="/js/app.module.js"></script>
<link media="screen" type="text/css" href="/css/reset.css">
<link media="screen" type="text/css" href="/css/jquery.myplugin.css">
<link media="screen" type="text/css" href="/css/app.css">
<link media="screen" type="text/css" href="/css/app.module.css">
<?php $bundle->end(); ?>
```

Output the bundle `<script>` and `<link>` tags wherever you want:

```php
<?php
// Renders both <script> and <link> tags
echo $bundle->render();

// Renders the <link> tag only
echo $bundle->renderCss();

// Renders the <script> tag only
echo $bundle->renderJs();
?>
```

### Using the Factory ###

You can also use a factory to create bundle instances. The advantage is, that the factory can hold global options (like `bypass` and `doc_root`) which are shared across all created bundles:

```php
<?php
$options = array(
    'doc_root' => '/path/to/your/document_root',
    'css_cache_path' => 'css/cache',
    'js_cache_path' => 'js/cache',
);

$factory = new \DotsUnited\BundleFu\Factory($options);

// $bundle1 and $bundle2 use the same doc_root, css_cache_path and js_cache_path options
$bundle1 = $factory->createBundle();
$bundle2 = $factory->createBundle();
?>
```

You can pass specific options to the `createBundle` method (global factory options will be overwritten):

```php
<?php
$bundle1 = $factory->createBundle(array('name' => 'bundle1', 'doc_root' => '/path/to/another/document_root'));
$bundle2 = $factory->createBundle(array('name' => 'bundle2'));
?>
```

The factory also lets you define name aliases for filters. You can then define the string alias for the `css_filter` and `js_filter` options instead of passing a filter instance:

```php
<?php
$filters = array(
    'js_closure_compiler' => new \DotsUnited\BundleFu\Filter\ClosureCompilerServiceFilter()
);

$factory = new \DotsUnited\BundleFu\Factory(array(), $filters);

$bundle1 = $factory->createBundle(array('js_filter' => 'js_closure_compiler'));
?>
```

Filters
-------

You can manipulate the loaded css/javascript files and the bundled css/javascript code with filters. Filters are classes which implement `DotsUnited\BundleFu\Filter\FilterInterface`.

You can add filters like this:

```php
<?php
$bundle->setCssFilter(new MyCssFilter());
$bundle->setJsFilter(my MyJsFilter());
?>
```

If you need multiple filters, you can use `DotsUnited\BundleFu\Filter\FilterChain` like this:

```php
<?php
$filterChain = new \DotsUnited\BundleFu\Filter\FilterChain();

$filterChain->addFilter(new MyCssFilter1());
$filterChain->addFilter(new MyCssFilter2());

$bundle->setCssFilter($filterChain);
?>
```

### Default filters ###

BundleFu provides the following filters out of the box.

#### CssUrlRewriteFilter ####

The `DotsUnited\BundleFu\Filter\CssUrlRewriteFilter` rewrites relative URLs in your CSS file to avoid broken image references:

```php
<?php
$bundle->setCssFilter(new \DotsUnited\BundleFu\Filter\CssUrlRewriteFilter());
?>
```

#### ClosureCompilerServiceFilter ####

This filter compiles javascript code with the [Google Closure Compiler](http://code.google.com/closure/compiler/) using the [Service API](http://code.google.com/closure/compiler/docs/api-ref.html).

Simply add the `DotsUnited\BundleFu\Filter\ClosureCompilerServiceFilter` filter and your javascript bundles will be automatically compiled:

```php
<?php
$bundle->setJsFilter(new \DotsUnited\BundleFu\Filter\ClosureCompilerServiceFilter());
?>
```

#### CallbackFilter ####

The `DotsUnited\BundleFu\Filter\CallbackFilter` can filter by using any PHP callback. If you want to compress your CSS using [YUI Compressor](http://developer.yahoo.com/yui/compressor/) you can either write a custom filter or use the following code leveraging the `Callback` filter:

```php
<?php
$filter = new \DotsUnited\BundleFu\Filter\CallbackFilter(function($content) {
    $descriptorspec = array(
         0 => array('pipe', 'r'),  // STDIN
         1 => array('pipe', 'w'),  // STDOUT
         2 => array('pipe', 'a')   // STDERR
    );

    $handle = proc_open('java -jar /path/to/yuicompressor.jar --type css' , $descriptorspec, $pipes);

    if (is_resource($handle)) {
        fwrite($pipes[0], $content);
        fclose($pipes[0]);

        $compressed = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        proc_close($handle);

        if ($compressed) {
            return $compressed;
        }
    }

    return $content;
});

$bundle->setCssFilter($filter);
?>
```

Notes
-----

  * All content inside of `$bundle->start()` and `$bundle->end()` will be lost. Be sure to only put css/javascript includes inside of the block.
  * Scripts/stylesheets are detected by parsing the output and looking for include files. HTML comments are ignored, so if you comment out a script like this:

    ```html
    <!-- <script src="/js/script.js" type="text/javascript"></script> -->
    ```

    the comment will be ignored and the file will be bundled anyways. Be sure to comment out via PHP:

    ```php
    <?php /* <script src="/js/script.js" type="text/javascript"></script> */ ?>
    ```

  * External dependencies via querystring loading will not work:

    ```html
    <script src="/js/scriptaculous.js?load=effects,controls" type="text/javascript"></script>
    ```

    Instead, you'll need to include each javascript file as normal.


License
-------

BundleFu is released under the [MIT License](https://github.com/dotsunited/BundleFu/blob/master/LICENSE).
