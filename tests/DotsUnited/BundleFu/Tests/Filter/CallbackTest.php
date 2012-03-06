<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2011 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DotsUnited\BundleFu\Tests\Filter;

use DotsUnited\BundleFu\Filter\Callback;

/**
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
class CallbackTest extends \PHPUnit_Framework_TestCase
{
    public function testCallback()
    {
        $called = false;
        $callback = function() use(&$called) {
            $called = true;
            return 'bar';
        };

        $filter = new Callback($callback);
        $result = $filter->filter('foo');

        $this->assertTrue($called);
        $this->assertEquals('bar', $result);
    }

    public function testFileCallback()
    {
        $called = false;
        $callbackFile = function() use(&$called) {
            $called = true;
            return 'bar';
        };

        $filter = new Callback(null, $callbackFile);
        $result = $filter->filterFile('foo', '/js/js_1.js', new \SplFileInfo(__DIR__ . '/_files/js/js_1.js'));

        $this->assertTrue($called);
        $this->assertEquals('bar', $result);
    }

    public function testNullCallbacks()
    {
        $filter = new Callback();

        $value = "foo";

        $this->assertEquals($value, $filter->filter($value));
        $this->assertEquals($value, $filter->filterFile($value, '/js/js_1.js', new \SplFileInfo(__DIR__ . '/_files/js/js_1.js')));
    }
}
