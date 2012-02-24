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

use DotsUnited\BundleFu\Filter\ClosureCompilerService;

/**
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 *
 * @group online
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
        $compiled = 'function js_1(){alert("hi")}function func(){alert("hi");return!0}function func(){alert("hi");return!0};';

        $this->assertEquals($compiled, trim($filter->filter($uncompiled)));
    }

    public function testFilterShouldAcceptParametersInContructor()
    {
        $filter = new ClosureCompilerService(array('compilation_level' => 'WHITESPACE_ONLY'));

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
