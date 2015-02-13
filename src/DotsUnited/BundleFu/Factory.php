<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2015 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DotsUnited\BundleFu;

use DotsUnited\BundleFu\Filter\FilterInterface;

/**
 * DotsUnited\BundleFu\Factory
 *
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
class Factory
{
    /**
     * Global bundle options.
     *
     * @var array
     */
    protected $options = array();

    /**
     * Filter map.
     *
     * @var array
     */
    protected $filters = array();

    /**
     * Contructor.
     *
     * @param array $options
     * @param array $filters
     */
    public function __construct(array $options = array(), array $filters = array())
    {
        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }

        foreach ($filters as $name => $filter) {
            $this->setFilter($name, $filter);
        }
    }

    /**
     * Set an option.
     *
     * @param  string                       $name
     * @param  mixed                        $value
     * @return \DotsUnited\BundleFu\Factory
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Set a filter.
     *
     * @param  string                                      $name
     * @param  \DotsUnited\BundleFu\Filter\FilterInterface $filter
     * @return \DotsUnited\BundleFu\Factory
     */
    public function setFilter($name, FilterInterface $filter = null)
    {
        $this->filters[$name] = $filter;

        return $this;
    }

    /**
     * Get a filter.
     *
     * @param  string                                      $name
     * @return \DotsUnited\BundleFu\Filter\FilterInterface $filter
     */
    public function getFilter($name)
    {
        if (!array_key_exists($name, $this->filters)) {
            throw new \RuntimeException('There is no filter for the name "' . $name . '" registered.');
        }

        return $this->filters[$name];
    }

    /**
     * Create a Bundle instance.
     *
     * @param  string|array                $options An array of options or a bundle name as string
     * @return \DotsUnited\BundleFu\Bundle
     * @throws \RuntimeException
     */
    public function createBundle($options = null)
    {
        if (!is_array($options)) {
            if (null !== $options) {
                $options = array('name' => $options);
            } else {
                $options = array();
            }
        }

        $options = array_merge($this->options, $options);

        if (isset($options['css_filter']) && is_string($options['css_filter'])) {
            $options['css_filter'] = $this->getFilter($options['css_filter']);
        }

        if (isset($options['js_filter']) && is_string($options['js_filter'])) {
            $options['js_filter'] = $this->getFilter($options['js_filter']);
        }

        return new Bundle($options);
    }
}
