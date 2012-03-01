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

use DotsUnited\BundleFu\Factory;

/**
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryPassesOptionsToBundle()
    {
        $options = array(
            'name'            => 'testbundle',
            'doc_root'        => '/my/custom/docroot',
            'bypass'          => true,
            'render_as_xhtml' => true,
            'css_filter'      => $this->getMock('\DotsUnited\BundleFu\Filter\FilterInterface'),
            'js_filter'       => $this->getMock('\DotsUnited\BundleFu\Filter\FilterInterface'),
            'css_cache_path'  => 'css/cache/path',
            'js_cache_path'   => 'js/cache/path',
            'css_cache_url'   => 'css/cache/url',
            'js_cache_url'    => 'js/cache/url',
        );

        $factory = new Factory($options);
        $bundle = $factory->createBundle();

        foreach ($options as $key => $val) {
            $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            $this->assertEquals($val, $bundle->$method(), ' -> ' . $key);
        }
    }

    public function testFactoryResolvesFilterNames()
    {
        $cssFilter = $this->getMock('DotsUnited\\BundleFu\\Filter\\FilterInterface');
        $jsFilter = $this->getMock('DotsUnited\\BundleFu\\Filter\\FilterInterface');

        $factory = new Factory(array(), array('css_filter' => $cssFilter, 'js_filter' => $jsFilter));

        $bundle = $factory->createBundle(array('css_filter' => 'css_filter', 'js_filter' => 'js_filter'));

        $this->assertEquals($cssFilter, $bundle->getCssFilter());
        $this->assertEquals($jsFilter, $bundle->getJsFilter());
    }

    public function testFactoryThrowExceptionForUnknowFilterName()
    {
        $this->setExpectedException('\\RuntimeException', 'There is no filter for the name "css_filter" registered.');

        $factory = new Factory();
        $factory->createBundle(array('css_filter' => 'css_filter'));
    }
    
    public function testFactoryAllowsSettingNullFilters()
    {
        $factory = new Factory(array(), array('css_filter' => null));
        $factory->createBundle(array('css_filter' => 'css_filter'));
    }

    public function testCreateBundleAcceptsArrayArgument()
    {
        $factory = new Factory(array('name' => 'foo'));
        $bundle = $factory->createBundle(array('name' => 'bar'));

        $this->assertEquals('bar', $bundle->getName());
    }

    public function testCreateBundleAcceptsStringArgument()
    {
        $factory = new Factory(array('name' => 'foo'));
        $bundle = $factory->createBundle('bar');

        $this->assertEquals('bar', $bundle->getName());
    }
}
