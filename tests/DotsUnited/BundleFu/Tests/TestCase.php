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

use DotsUnited\BundleFu\Bundle;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @var Bundle
     */
    protected $bundle;

    public function setUp()
    {
        $this->bundle = new Bundle();
        $this->bundle->setDocRoot(__DIR__ . '/_files');
    }

    public function tearDown()
    {
        $this->purgeCache();
        $this->bundle = null;
    }

    /**************************************************************************/

    public function assertFileMatch($filename, $needle, $message = null)
    {
        if (null === $message) {
            $message = "expected {$filename} to match {$needle}, but doesn't.";
        }

        $this->assertFileExists($filename);
        $this->assertRegExp('/' . preg_quote($needle, '/') . '/', file_get_contents($filename), $message);
    }

    public function assertFileNotMatch($filename, $needle, $message = null)
    {
        if (null === $message) {
            $message = "expected {$filename} to not match {$needle}, but does.";
        }

        $this->assertFileExists($filename);
        $this->assertNotRegExp('/' . preg_quote($needle, '/') . '/', file_get_contents($filename), $message);
    }

    protected function appendToFile($filename, $content)
    {
        $this->assertFileExists($filename);
        file_put_contents($filename, $content, FILE_APPEND);
    }

    protected function purgeCache()
    {
        $paths = array(
            __DIR__ . '/_files/css/cache',
            __DIR__ . '/_files/js/cache'
        );

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                continue;
            }

            foreach (glob($path . '/*') as $file) {
                if ($file[0] == '.') {
                    continue;
                }
                unlink($file);
            }

            rmdir($path);
        }
    }

    protected function includeSome()
    {
        return '<script src="/js/js_1.js?1000" type="text/javascript"></script>' . "\n" .
               '<script src="/js/js_2.js?1000" type="text/javascript"></script>' . "\n" .
               '<link href="/css/css_1.css?1000" media="screen" rel="stylesheet" type="text/css">' . "\n" .
               '<link href="/css/css_2.css?1000" media="screen" rel="stylesheet" type="text/css">';
    }

    protected function includeAll()
    {
        return $this->includeSome() . "\n" .
               '<script src="/js/js_3.js?1000" type="text/javascript"></script>' . "\n" .
               '<link href="/css/css_3.css?1000" media="screen" rel="stylesheet" type="text/css" />';
    }
}
