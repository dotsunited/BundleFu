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
 * @category   Du
 * @package    Du_BundleFu
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 */

namespace Du\BundleFu\Filter;

/**
 *  Du\BundleFu\Filter\FilterChain
 *
 * @category   Du
 * @package    Du_BundleFu
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 * @version    Release: @package_version@
 */
class FilterChain implements Filter
{
    const CHAIN_APPEND  = 'append';
    const CHAIN_PREPEND = 'prepend';

    /**
     * Filter chain
     *
     * @var array
     */
    protected $_filters = array();

    /**
     * Adds a filter to the chain
     *
     * @param Filter $filter
     * @param string $placement
     * @return FilterChain
     */
    public function addFilter(Filter $filter, $placement = self::CHAIN_APPEND)
    {
        if ($placement == self::CHAIN_PREPEND) {
            array_unshift($this->_filters, $filter);
        } else {
            $this->_filters[] = $filter;
        }
        return $this;
    }

    /**
     * Add a filter to the end of the chain
     *
     * @param Filter $filter
     * @return FilterChain
     */
    public function appendFilter(Filter $filter)
    {
        return $this->addFilter($filter, self::CHAIN_APPEND);
    }

    /**
     * Add a filter to the start of the chain
     *
     * @param Filter $filter
     * @return FilterChain
     */
    public function prependFilter(Filter $filter)
    {
        return $this->addFilter($filter, self::CHAIN_PREPEND);
    }

    /**
     * Get all the filters
     *
     * @return Filter[]
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * Reset all the filters
     *
     * @return FilterChain
     */
    public function resetFilters()
    {
        $this->_filters = array();
        return $this;
    }

    /**
     * Returns $content filtered through each filter in the chain
     *
     * Filters are run in the order in which they were added to the chain (FIFO)
     *
     * @param mixed $content
     * @return mixed
     */
    public function filter($content)
    {
        $contentFiltered = $content;
        foreach ($this->_filters as $filter) {
            $contentFiltered = $filter->filter($contentFiltered);
        }
        return $contentFiltered;
    }
}
