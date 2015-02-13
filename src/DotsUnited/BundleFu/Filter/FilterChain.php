<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2015 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DotsUnited\BundleFu\Filter;

/**
 *  DotsUnited\BundleFu\Filter\FilterChain
 *
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
class FilterChain implements FilterInterface
{
    const CHAIN_APPEND  = 'append';
    const CHAIN_PREPEND = 'prepend';

    /**
     * Filter chain
     *
     * @var array
     */
    protected $filters = array();

    /**
     * Adds a filter to the chain
     *
     * @param  FilterInterface $filter
     * @param  string          $placement
     * @return FilterChain
     */
    public function addFilter(FilterInterface $filter, $placement = self::CHAIN_APPEND)
    {
        if ($placement == self::CHAIN_PREPEND) {
            array_unshift($this->filters, $filter);
        } else {
            $this->filters[] = $filter;
        }

        return $this;
    }

    /**
     * Add a filter to the end of the chain
     *
     * @param  FilterInterface $filter
     * @return FilterChain
     */
    public function appendFilter(FilterInterface $filter)
    {
        return $this->addFilter($filter, self::CHAIN_APPEND);
    }

    /**
     * Add a filter to the start of the chain
     *
     * @param  FilterInterface $filter
     * @return FilterChain
     */
    public function prependFilter(FilterInterface $filter)
    {
        return $this->addFilter($filter, self::CHAIN_PREPEND);
    }

    /**
     * Get all the filters
     *
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Reset all the filters
     *
     * @return FilterChain
     */
    public function resetFilters()
    {
        $this->filters = array();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function filter($content)
    {
        $contentFiltered = $content;

        foreach ($this->filters as $filter) {
            $contentFiltered = $filter->filter($contentFiltered);
        }

        return $contentFiltered;
    }

    /**
     * {@inheritDoc}
     */
    public function filterFile($content, $file, \SplFileInfo $fileInfo, $bundleUrl, $bundlePath)
    {
        $contentFiltered = $content;

        foreach ($this->filters as $filter) {
            $contentFiltered = $filter->filterFile($contentFiltered, $file, $fileInfo, $bundleUrl, $bundlePath);
        }

        return $contentFiltered;
    }
}
