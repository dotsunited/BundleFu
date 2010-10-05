<?php
/**
 * Du
 *
 * LICENSE
 *
 * This source file is subject to the BSD license that is available
 * through the world-wide-web at this URL:
 * http://opensource.org/licenses/bsd-license.php
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace Du\BundleFu\Filter;

/**
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage UnitTests
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class ClosureCompilerServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterShouldCompileContents()
    {
        $filter = new ClosureCompilerService();

        $uncompiled = "function js_1() { alert('hi')};

// this is a function
function func() {
  alert('hi')
  return true
}

function func() {
  alert('hi')
  return true
}
";
        $compiled = 'function js_1(){alert("hi")}function func(){alert("hi");return true}function func(){alert("hi");return true};';

        $this->assertEquals($compiled, trim($filter->filter($uncompiled)));
    }

    public function testFilterInvalidCodeShouldReturnOriginalContent()
    {
        $filter = new ClosureCompilerService();

        $uncompiled = "function js_1() {";

        $this->assertEquals($uncompiled, trim($filter->filter($uncompiled)));
    }
}
