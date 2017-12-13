<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2015 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DotsUnited\BundleFu\Tests;

use DotsUnited\BundleFu\Filter\CssUrlRewriteFilter;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Some parts taken from https://github.com/kriswallsmith/assetic/blob/master/tests/Assetic/Test/Filter/CssUrlRewriteFilterTest.php
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
class CssUrlRewriteFilterTest extends BaseTestCase
{
    /**
     * @dataProvider provideUrls
     */
    public function testUrls($format, $file, $bundleUrl, $inputUrl, $expectedUrl)
    {
        $content = sprintf($format, $inputUrl);

        $fileInfo = new \SplFileInfo($file);

        $filter = new CssUrlRewriteFilter();
        $filtered = $filter->filterFile($content, $file, $fileInfo, $bundleUrl, null);

        $this->assertEquals(sprintf($format, $expectedUrl), $filtered, '->filterFile() rewrites relative urls');
    }

    public function provideUrls()
    {
        return array(
            // url variants
            array('body { background: url(%s); }', '/css/body.css', '/css/build/main.css', '../images/bg.gif', '../../images/bg.gif'),
            array('body { background: url("%s"); }', '/css/body.css', '/css/build/main.css', '../images/bg.gif', '../../images/bg.gif'),
            array('body { background: url(\'%s\'); }', '/css/body.css', '/css/build/main.css', '../images/bg.gif', '../../images/bg.gif'),

            // url with data:
            array('body { background: url(\'%s\'); }', '/css/body.css', '/css/build/main.css', 'data:image/png;base64,abcdef=', 'data:image/png;base64,abcdef='),
            array('body { background: url(\'%s\'); }', '/css/body.css', '/css/build/main.css', '../images/bg-data:.gif', '../../images/bg-data:.gif'),

            // @import variants
            array('@import "%s";', '/css/imports.css', '/css/build/main.css', 'import.css', '../import.css'),
            array('@import url(%s);', '/css/imports.css', '/css/build/main.css', 'import.css', '../import.css'),
            array('@import url("%s");', '/css/imports.css', '/css/build/main.css', 'import.css', '../import.css'),
            array('@import url(\'%s\');', '/css/imports.css', '/css/build/main.css', 'import.css', '../import.css'),

            // path diffs
            array('body { background: url(%s); }', '/css/body/bg.css', '/css/build/main.css', '../../images/bg.gif', '../../images/bg.gif'),
            array('body { background: url(%s); }', '/css/body.css', '/main.css', '../images/bg.gif', 'images/bg.gif'), // fixme
            array('body { background: url(%s); }', '/body.css', '/css/main.css', 'images/bg.gif', '../images/bg.gif'),
            array('body { background: url(%s); }', '/css/source/body.css', '/css/main.css', '../../images/bg.gif', '../images/bg.gif'),
            array('body { background: url(%s); }', '/source/css/body.css', '/output/build/main.css', '../images/bg.gif', '../../source/images/bg.gif'),

            // path diffs with absolute bundle urls
            array('body { background: url(%s); }', '/css/body/bg.css', 'http://foo.com/css/build/main.css', '../../images/bg.gif', '../../images/bg.gif'),
            array('body { background: url(%s); }', '/css/body.css', 'http://foo.com/main.css', '../images/bg.gif', 'images/bg.gif'), // fixme
            array('body { background: url(%s); }', '/body.css', 'http://foo.com/css/main.css', 'images/bg.gif', '../images/bg.gif'),
            array('body { background: url(%s); }', '/css/source/body.css', 'http://foo.com/css/main.css', '../../images/bg.gif', '../images/bg.gif'),
            array('body { background: url(%s); }', '/source/css/body.css', 'http://foo.com/output/build/main.css', '../images/bg.gif', '../../source/images/bg.gif'),

            // path diffs with protocol-relative bundle urls
            array('body { background: url(%s); }', '/css/body/bg.css', '//foo.com/css/build/main.css', '../../images/bg.gif', '../../images/bg.gif'),
            array('body { background: url(%s); }', '/css/body.css', '//foo.com/main.css', '../images/bg.gif', 'images/bg.gif'), // fixme
            array('body { background: url(%s); }', '/body.css', '//foo.com/css/main.css', 'images/bg.gif', '../images/bg.gif'),
            array('body { background: url(%s); }', '/css/source/body.css', '//foo.com/css/main.css', '../../images/bg.gif', '../images/bg.gif'),
            array('body { background: url(%s); }', '/source/css/body.css', '//foo.com/output/build/main.css', '../images/bg.gif', '../../source/images/bg.gif'),

            // url diffs
            array('body { background: url(%s); }', '/css/body.css', '/css/build/main.css', 'http://foo.com/bar.gif', 'http://foo.com/bar.gif'),
            array('body { background: url(%s); }', '/css/body.css', '/css/build/main.css', '/images/foo.gif', '/images/foo.gif'),
            array('body { background: url(%s); }', '/css/body.css', '/css/build/main.css', 'http://foo.com/images/foo.gif', 'http://foo.com/images/foo.gif'),
            array('body { background: url(%s); }', '/css/body.css', '/css/build/main.css', '//foo.com/images/bg.gif', '//foo.com/images/bg.gif'),

            // AlphaImageLoader
            array('body { filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="%s", sizingMethod="scale") }', '/css/body.css', '/css/build/main.css', '../images/bg.gif', '../../images/bg.gif'),
            array('body { filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod = "scale", src = "%s") }', '/css/body.css', '/css/build/main.css', '../images/bg.gif', '../../images/bg.gif'),
        );
    }

    /**
     * @dataProvider provideMultipleUrls
     */
    public function testMultipleUrls($format, $file, $bundleUrl, $inputUrl1, $inputUrl2, $expectedUrl1, $expectedUrl2)
    {
        $content = sprintf($format, $inputUrl1, $inputUrl2);

        $fileInfo = new \SplFileInfo($file);

        $filter = new CssUrlRewriteFilter();
        $filtered = $filter->filterFile($content, $file, $fileInfo, $bundleUrl, null);

        $this->assertEquals(sprintf($format, $expectedUrl1, $expectedUrl2), $filtered, '->filterFile() rewrites relative urls');
    }

    public function provideMultipleUrls()
    {
        return array(
            // multiple url
            array('body { background: url(%s); background: url(%s); }', 'css/body.css', 'css/build/main.css', '../images/bg.gif', '../images/bg2.gif', '../../images/bg.gif', '../../images/bg2.gif'),
            array("body { background: url(%s);\nbackground: url(%s); }", 'css/body.css', 'css/build/main.css', '../images/bg.gif', '../images/bg2.gif', '../../images/bg.gif', '../../images/bg2.gif'),

            // multiple import
            array('@import "%s"; @import "%s";', 'css/imports.css', 'css/build/main.css', 'import.css', 'import2.css', '../import.css', '../import2.css'),
            array("@import \"%s\";\n@import \"%s\";", 'css/imports.css', 'css/build/main.css', 'import.css', 'import2.css', '../import.css', '../import2.css'),

            // mixed urls and imports
            array('@import "%s"; body { background: url(%s); }', 'css/body.css', 'css/build/main.css', 'import.css', '../images/bg2.gif', '../import.css', '../../images/bg2.gif'),
            array("@import \"%s\";\nbody { background: url(%s); }", 'css/body.css', 'css/build/main.css', 'import.css', '../images/bg2.gif', '../import.css', '../../images/bg2.gif'),
        );
    }
}
