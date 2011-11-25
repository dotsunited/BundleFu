BundleFu
========

[![Build Status](https://secure.travis-ci.org/dotsunited/BundleFu.png)](http://travis-ci.org/dotsunited/BundleFu)

BundleFu is a PHP 5.3+ library which bundles multiple css/javascript files into a big package and sends it out at once.

**Note**: If you need a PHP 5.2 version of this BundleFu, checkout the [5.2 branch](https://github.com/dotsunited/BundleFu/tree/5.2).

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

## Features ##

  * Automatically detects modifications to your css and javascript files and regenerates the bundles automatically.
  * Rewrites relative URLs in your css files to avoid broken image references.
  * Bundle contents can be modified by filters for code minification, compression etc. (A [Google Closure Compiler](http://code.google.com/closure/compiler/) filter using the [Service API](http://code.google.com/closure/compiler/docs/api-ref.html) comes with the library).

## Installation ##

You can install BundleFu via the [Dots United PEAR channel](http://pear.dotsunited.de). Run this from your command line:

```
pear channel-discover pear.dotsunited.de
pear install dotsunited/BundleFu-beta
```

## Prerequisites ##

BundleFu needs at least PHP 5.3.0 to run and requires that you have setup autoloading (BundleFu follows the technical interoperability [standards](http://groups.google.com/group/php-standards/web/psr-0-final-proposal) for PHP 5.3 namespaces and class names).

Most modern frameworks have tools to setup autoloading (e.g. [Symfony2](http://docs.symfony-reloaded.org/guides/tools/autoloader.html)), if you are unsure you can use the following code snippet in your bootstrap file:

```php
<?php
spl_autoload_register(function($className) {
    if (strpos($className, 'DotsUnited\\BundleFu\\') === 0) {
        require str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    }
});
?>
```

This requires that you have installed BundleFu in your `include_path` which is already the case if you have installed it via PEAR.

## Usage ##

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

## Filters ##

You can manipulate the bundled css/javascript code with filters. Filters are classes which implement `DotsUnited\BundleFu\Filter\FilterInterface`.

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

### Examples ###

BundleFu provides a filter to compile javascript code with the [Google Closure Compiler](http://code.google.com/closure/compiler/) using the [Service API](http://code.google.com/closure/compiler/docs/api-ref.html).

Simply add the `DotsUnited\BundleFu\Filter\ClosureCompilerService` filter and your javascript bundles will be automatically compiled:

```php
<?php
$bundle->setJsFilter(new \DotsUnited\BundleFu\Filter\ClosureCompilerService());
?>
```

The `DotsUnited\BundleFu\Filter\Callback` can filter by using any PHP callback. If you want to compress your CSS using [YUI Compressor](http://developer.yahoo.com/yui/compressor/) you can either write a custom filter or use the following code leveraging the `Callback` filter:

```php
<?php
$filter = new \DotsUnited\BundleFu\Filter\Callback(function($content) {
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

## Notes ##

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

## License ##

BundleFu is released under the [New BSD License](https://github.com/dotsunited/BundleFu/blob/master/LICENSE).
