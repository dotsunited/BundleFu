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
 * @category Du
 * @package Du_BundleFu
 * @subpackage UnitTests
 * @copyright Copyright (C) 2010 - Present, Jan Sorgalla
 * @license BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 */

namespace Du\BundleFu\Filter;

/**
 * @category Du
 * @package Du_BundleFu
 * @subpackage UnitTests
 * @author Jan Sorgalla
 * @copyright Copyright (C) 2010 - Present, Jan Sorgalla
 * @license BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 *
 * @covers Du\BundleFu\Filter\Callback
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
}
