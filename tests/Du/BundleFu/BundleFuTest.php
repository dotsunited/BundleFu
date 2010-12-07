<?php
/**
 * Du
 *
 * LICENSE
 *
 * This source file is subject to the BSD license that is available
 * through the world-wide-web at this URL:
 * https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 */

namespace Du\BundleFu;

use Du\BundleFu\Filter\Callback as CallbackFilter;

/**
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage UnitTests
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 */
class BundleFuTest extends TestCase
{
    public function testGetCssBundleUrlWithAbsoluteCssCachePathAndNoCssCacheUrlSetShouldThrowException()
    {
        $this->setExpectedException('Du\BundleFu\RuntimeException', 'If you do not provide a css cache url, css cache path must be a relative local path...');
        $this->_bundleFu->setCssCachePath('/absolute/path');
        $this->_bundleFu->getCssBundleUrl();
    }

    public function testGetJsBundleUrlWithAbsoluteJsCachePathAndNoJsCacheUrlSetShouldThrowException()
    {
        $this->setExpectedException('Du\BundleFu\RuntimeException', 'If you do not provide a js cache url, js cache path must be a relative local path...');
        $this->_bundleFu->setJsCachePath('/absolute/path');
        $this->_bundleFu->getJsBundleUrl();
    }

    public function testEndWithoutPriorBundleCallShouldThrowException()
    {
        $this->setExpectedException('Du\BundleFu\RuntimeException', 'end() is called without a start() call.');
        $this->_bundleFu->end();
    }

    public function testEndWithoutSettingDocRootFirstShouldThrowException()
    {
        $this->setExpectedException('Du\BundleFu\RuntimeException', 'Please set a document root either with setDocRoot() or via runtime through bundle options.');

        $bundleFu = new BundleFu();

        $bundleFu->start();
        $bundleFu->end();
    }

    public function testCastingInstanceToStringShouldCallRender()
    {
        $this->_bundleFu->start();
        echo $this->_includeAll();
        $this->_bundleFu->end();

        $this->assertEquals($this->_bundleFu->render(), (string) $this->_bundleFu);
    }

    /**************************************************************************/

    public function testBundleShouldUseCssFilters()
    {
        $called = false;
        $callback = function($content) use(&$called) {
            $called = true;
            return 'filtered';
        };
        $this->_bundleFu->setCssFilter(new CallbackFilter($callback));

        $this->_bundleFu->start();
        echo '<link href="/css/css_1.css?1000" media="screen" rel="stylesheet" type="text/css">';
        $this->_bundleFu->end();

        $rendered = $this->_bundleFu->render();

        $this->assertTrue($called);
        $this->assertFileMatch($this->_bundleFu->getCssBundlePath(), 'filtered');
    }

    public function testBundleShouldUseJsFilters()
    {
        $called = false;
        $callback = function($content) use(&$called) {
            $called = true;
            return 'filtered';
        };
        $this->_bundleFu->setJsFilter(new CallbackFilter($callback));

        $this->_bundleFu->start();
        echo '<script src="/js/js_1.js?1000" type="text/javascript"></script>';
        $this->_bundleFu->end();

        $rendered = $this->_bundleFu->render();

        $this->assertTrue($called);
        $this->assertFileMatch($this->_bundleFu->getJsBundlePath(), 'filtered');
    }

    public function testSetCssCacheUrlShouldBeUsedInOutput()
    {
        $this->_bundleFu->setCssCacheUrl('http://mycdn.org');

        $this->_bundleFu->start();
        echo '<link href="/css/css_1.css?1000" media="screen" rel="stylesheet" type="text/css">';
        $this->_bundleFu->end();

        $rendered = $this->_bundleFu->render();

        $this->assertRegExp('/<link href="http:\/\/mycdn.org[^"]+" rel="stylesheet" type="text\/css">/', $rendered);
    }

    public function testSetJsCacheUrlShouldBeUsedInOutput()
    {
        $this->_bundleFu->setJsCacheUrl('http://mycdn.org');

        $this->_bundleFu->start();
        echo '<script src="/js/js_1.js?1000" type="text/javascript"></script>';
        $this->_bundleFu->end();

        $rendered = $this->_bundleFu->render();

        $this->assertRegExp('/<script src="http:\/\/mycdn.org[^"]+" type="text\/javascript"><\/script>/', $rendered);
    }

    public function testBundleShouldGenerateNonXhtmlByDefault()
    {
        $this->_bundleFu->start();
        echo '<link href="/css/css_1.css?1000" media="screen" rel="stylesheet" type="text/css">';
        $this->_bundleFu->end();

        $rendered = $this->_bundleFu->render();

        $this->assertRegExp('/<link href="[^"]+" rel="stylesheet" type="text\/css">/', $rendered);
    }

    public function testBundleShouldGenerateXhtmlIfSetRenderAsXhtmlIsCalledWithTrue()
    {
        $this->_bundleFu->setRenderAsXhtml(true);

        $this->_bundleFu->start();
        echo '<link href="/css/css_1.css?1000" media="screen" rel="stylesheet" type="text/css">';
        $this->_bundleFu->end();

        $rendered = $this->_bundleFu->render();

        $this->assertRegExp('/<link href="[^"]+" rel="stylesheet" type="text\/css" \/>/', $rendered);
    }

    public function testBundleJsFilesShouldIncludeJsContent()
    {
        $this->_bundleFu->start();
        echo $this->_includeAll();
        $this->_bundleFu->end();

        $this->_bundleFu->render();

        $this->assertFileMatch($this->_bundleFu->getJsBundlePath(), "function js_1()");
    }

    public function testBundleJsFilesWithAssetServerUrl()
    {
        $this->_bundleFu->start();
        echo '<script src="https://assets.server.com/js/js_1.js?1000" type="text/javascript"></script>';
        $this->_bundleFu->end();

        $this->_bundleFu->render();

        $this->assertFileMatch($this->_bundleFu->getJsBundlePath(), "function js_1()");
    }

    public function testContentRemainsSameShouldntRefreshCache()
    {
        $this->_bundleFu->start();
        echo $this->_includeSome();
        $this->_bundleFu->end();

        $this->_bundleFu->render();

        // check to see each bundle file exists and append some text to the bottom of each file
        $this->_appendToFile($this->_bundleFu->getCssBundlePath(), "BOGUS");
        $this->_appendToFile($this->_bundleFu->getJsBundlePath(), "BOGUS");

        $this->assertFileMatch($this->_bundleFu->getCssBundlePath(), "BOGUS");
        $this->assertFileMatch($this->_bundleFu->getJsBundlePath(), "BOGUS");

        $this->_bundleFu->getCssFileList()->reset();
        $this->_bundleFu->getJsFileList()->reset();

        $this->_bundleFu->start();
        echo $this->_includeSome();
        $this->_bundleFu->end();

        $this->_bundleFu->render();

        $this->assertFileMatch($this->_bundleFu->getCssBundlePath(), "BOGUS");
        $this->assertFileMatch($this->_bundleFu->getJsBundlePath(), "BOGUS");
    }

    public function testContentChangesShouldRefreshCache()
    {
        $this->_bundleFu->start();
        echo $this->_includeSome();
        $this->_bundleFu->end();

        $this->_bundleFu->render();

        $this->_appendToFile($this->_bundleFu->getCssBundlePath(), "BOGUS");
        $this->_appendToFile($this->_bundleFu->getJsBundlePath(), "BOGUS");

        $this->assertFileMatch($this->_bundleFu->getCssBundlePath(), "BOGUS");
        $this->assertFileMatch($this->_bundleFu->getJsBundlePath(), "BOGUS");

        $this->_bundleFu->getCssFileList()->reset();
        $this->_bundleFu->getJsFileList()->reset();

        $this->_bundleFu->start();
        echo $this->_includeAll();
        $this->_bundleFu->end();

        $this->_bundleFu->render();

        $this->assertFileNotMatch($this->_bundleFu->getCssBundlePath(), "BOGUS");
        $this->assertFileNotMatch($this->_bundleFu->getJsBundlePath(), "BOGUS");
    }

    public function testBundleJsOnlyShouldOutputJsIncludeStatement()
    {
        $this->_bundleFu->start();
        list($first) = explode("\n", $this->_includeSome());
        echo $first;
        $this->_bundleFu->end();

        $output = $this->_bundleFu->render();
        $split = explode("\n", $output);

        $this->assertEquals(1, count($split));
        $this->assertRegExp('/js/', $split[0]);
    }

    public function testBundleCssOnlyShouldOutputCssIncludeStatement()
    {
        $this->_bundleFu->start();
        list($first, $second, $third) = explode("\n", $this->_includeSome());
        echo $third;
        $this->_bundleFu->end();

        $output = $this->_bundleFu->render();
        $split = explode("\n", $output);

        $this->assertEquals(1, count($split));
        $this->assertRegExp('/css/', $split[0]);
    }

    public function testNonexistingFileShouldOutputFileReadErrorStatement()
    {
        $this->_bundleFu->start();
        echo '<link href="/css/non_existing_file.css?1000" media="screen" rel="stylesheet" type="text/css">';
        echo '<script src="/js/non_existing_file.js?1000" type="text/javascript"></script>';
        $this->_bundleFu->end();

        $this->_bundleFu->render();

        $this->assertFileMatch($this->_bundleFu->getCssBundlePath(), "FILE READ ERROR");
        $this->assertFileMatch($this->_bundleFu->getJsBundlePath(), "FILE READ ERROR");
    }

    public function testBypassShouldRenderNormalOutput()
    {
        ob_start();

        $this->_bundleFu->start(array('bypass' => true));
        echo $this->_includeAll();
        $this->_bundleFu->end();

        $contents = ob_get_clean();

        $this->_bundleFu->render();

        $this->assertEquals($this->_includeAll(), $contents);

        $this->_bundleFu->getCssFileList()->reset();
        $this->_bundleFu->getJsFileList()->reset();

        $this->_bundleFu->setBypass(true);

        ob_start();

        $this->_bundleFu->start();
        echo $this->_includeSome();
        $this->_bundleFu->end();

        $this->_bundleFu->start();
        echo $this->_includeAll();
        $this->_bundleFu->end();

        $contents = ob_get_clean();

        $this->_bundleFu->render();

        $this->assertEquals($this->_includeSome() . $this->_includeAll(), $contents);
    }

    public function testBundleCssFileShouldRewriteRelativePath()
    {
        $this->_bundleFu->start();
        echo $this->_includeAll();
        $this->_bundleFu->end();

        $this->_bundleFu->render();

        $this->assertFileMatch($this->_bundleFu->getCssBundlePath(), "background-image: url(/images/background.gif)");
        $this->assertFileMatch($this->_bundleFu->getCssBundlePath(), "background-image: url(/images/groovy/background_2.gif)");
    }

    public function testRewriteRelativePathShouldRewrite()
    {
        $this->assertEquals(
            '/images/spinner.gif',
            $this->_bundleFu->getCssUrlRewriter()->rewriteRelativePath(
                '/stylesheets/active_scaffold/default/stylesheet.css',
                '../../../images/spinner.gif'
            )
        );

        $this->assertEquals(
            '/images/spinner.gif',
            $this->_bundleFu->getCssUrlRewriter()->rewriteRelativePath(
                '/stylesheets/active_scaffold/default/stylesheet.css',
                '../../../images/./../images/goober/../spinner.gif'
            )
        );

        $this->assertEquals(
            '/images/spinner.gif',
            $this->_bundleFu->getCssUrlRewriter()->rewriteRelativePath(
                'stylesheets/active_scaffold/default/./stylesheet.css',
                '../../../images/spinner.gif'
            )
        );

        $this->assertEquals(
            '/stylesheets/image.gif',
            $this->_bundleFu->getCssUrlRewriter()->rewriteRelativePath(
                'stylesheets/main.css',
                'image.gif'
            )
        );

        $this->assertEquals(
            '/stylesheets/image.gif',
            $this->_bundleFu->getCssUrlRewriter()->rewriteRelativePath(
                '/stylesheets////default/main.css',
                '..//image.gif'
            )
        );

        $this->assertEquals(
            '/images/image.gif',
            $this->_bundleFu->getCssUrlRewriter()->rewriteRelativePath(
                '/stylesheets/default/main.css',
                '/images/image.gif'
            )
        );
    }

    public function testRewriteRelativePathShouldntRewriteIfAbsoluteUrl()
    {
        $this->assertEquals(
            'http://www.url.com/images/image.gif',
            $this->_bundleFu->getCssUrlRewriter()->rewriteRelativePath(
                'stylesheets/main.css',
                'http://www.url.com/images/image.gif'
            )
        );

        $this->assertEquals(
            'ftp://www.url.com/images/image.gif',
            $this->_bundleFu->getCssUrlRewriter()->rewriteRelativePath(
                'stylesheets/main.css',
                'ftp://www.url.com/images/image.gif'
            )
        );
    }

    public function testRewriteRelativePathShouldStripSpacesAndQuotes()
    {
        $this->assertEquals(
            'background-image: url(/stylesheets/image.gif)',
            $this->_bundleFu->getCssUrlRewriter()->rewriteUrls(
                'stylesheets/main.css',
                'background-image: url(\'image.gif\')'
            )
        );

        $this->assertEquals(
            'background-image: url(/stylesheets/image.gif)',
            $this->_bundleFu->getCssUrlRewriter()->rewriteUrls(
                'stylesheets/main.css',
                'background-image: url("image.gif")'
            )
        );

        $this->assertEquals(
            'background-image: url(/stylesheets/image.gif)',
            $this->_bundleFu->getCssUrlRewriter()->rewriteUrls(
                'stylesheets/main.css',
                'background-image: url( image.gif )'
            )
        );

        $this->assertEquals(
            'background-image: url(/stylesheets/image.gif)',
            $this->_bundleFu->getCssUrlRewriter()->rewriteUrls(
                'stylesheets/main.css',
                'background-image: url( "image.gif ")'
            )
        );
    }
}
