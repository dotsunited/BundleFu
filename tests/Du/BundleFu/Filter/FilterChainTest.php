<?php
/**
 * Du\BundleFu
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
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 */

namespace Du\BundleFu\Filter;

/**
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage UnitTests
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 */
class FilterChainTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $filter = new FilterChain();
        $value = 'something';
        $this->assertEquals($value, $filter->filter($value));
    }

    public function testFilterOrder()
    {
        $filter = new FilterChain();
        $filter->addFilter(new LowerCase())
               ->addFilter(new StripUpperCase());
        $value = 'AbC';
        $valueExpected = 'abc';
        $this->assertEquals($valueExpected, $filter->filter($value));
    }

    public function testFilterPrependOrder()
    {
        $filter = new FilterChain();
        $filter->appendFilter(new StripUpperCase())
               ->prependFilter(new LowerCase());
        $value = 'AbC';
        $valueExpected = 'abc';
        $this->assertEquals($valueExpected, $filter->filter($value));
    }

    public function testFilterReset()
    {
        $filter = new FilterChain();
        $filter->appendFilter(new StripUpperCase())
               ->prependFilter(new LowerCase());

        $filter->resetFilters();

        $value = 'AbC';
        $valueExpected = 'AbC';
        $this->assertEquals($valueExpected, $filter->filter($value));
    }

    public function testGetFilters()
    {
        $filter = new FilterChain();

        $filter1 = new StripUpperCase();
        $filter2 = new LowerCase();

        $filter->appendFilter($filter1)
               ->prependFilter($filter2);

        $array = $filter->getFilters();

        $this->assertEquals($filter2, $array[0]);
        $this->assertEquals($filter1, $array[1]);
    }
}


class LowerCase implements Filter
{
    public function filter($value)
    {
        return strtolower($value);
    }
}


class StripUpperCase implements Filter
{
    public function filter($value)
    {
        return preg_replace('/[A-Z]/', '', $value);
    }
}
