<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2011 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DotsUnited\BundleFu\Tests;

use DotsUnited\BundleFu\Filter\CssOptimizeFilter;

/**
 *
 * @author Claudio Beatrice <claudi0.beatric3@gmail.com>
 * @version @package_version@
 */
class CssOptimizeFilterTest extends \PHPUnit_Framework_TestCase
{
    protected $content;

    public function __construct()
    {
        // the test fixture is always the same, so there's no need to reload it for every test
        $this->content = file_get_contents(__DIR__ . '/../_files/css/css_to_be_optimized.css');
    }

    public function testOptimizeWithColorOptimization()
    {
        $filter = new CssOptimizeFilter();

        $expectedContent = file_get_contents(__DIR__ . '/../_files/css/css_optimized_with_colors.css');

        $this->assertEquals(
            $expectedContent,
            $filter->filterFile($this->content)
        );
    }

    public function testOptimizeWithoutColorOptimization()
    {
        $filter = new CssOptimizeFilter(array(
            'optimizeColors' => false,
        ));

        $expectedContent = file_get_contents(__DIR__ . '/../_files/css/css_optimized_without_colors.css');

        $this->assertEquals(
            $expectedContent,
            $filter->filterFile($this->content)
        );
    }
}