<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2011 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DotsUnited\BundleFu\Filter;

/**
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
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


class LowerCase implements FilterInterface
{
    public function filter($value)
    {
        return strtolower($value);
    }
}


class StripUpperCase implements FilterInterface
{
    public function filter($value)
    {
        return preg_replace('/[A-Z]/', '', $value);
    }
}
