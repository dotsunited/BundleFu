<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2015 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DotsUnited\BundleFu\Tests\Filter;

use DotsUnited\BundleFu\Filter\ClosureCompilerServiceFilter;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 *
 * @group online
 */
class ClosureCompilerServiceFilterTest extends BaseTestCase
{
    public function testFilterShouldCompileContents()
    {
        $filter = new ClosureCompilerServiceFilter();

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
        $filter = new ClosureCompilerServiceFilter(array('compilation_level' => 'WHITESPACE_ONLY'));

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
        $filter = new ClosureCompilerServiceFilter();

        $uncompiled = "function js_1() {";

        $this->assertEquals($uncompiled, trim($filter->filter($uncompiled)));
    }

    public function testFilterFileDoesNothing()
    {
        $filter = new ClosureCompilerServiceFilter();

        $value = "function js_1() {";

        $this->assertEquals($value, $filter->filterFile($value, '/js/js_1.js', new \SplFileInfo(__DIR__ . '/_files/js/js_1.js'), null, null));
    }
}
