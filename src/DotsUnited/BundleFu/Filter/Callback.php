<?php
/**
 * DotsUnited\BundleFu
 *
 * LICENSE
 *
 * This source file is subject to the BSD license that is available
 * through the world-wide-web at this URL:
 * https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Filter
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 */

namespace DotsUnited\BundleFu\Filter;

/**
 *  DotsUnited\BundleFu\Filter\Callback
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Filter
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 * @version    @package_version@
 */
class Callback implements Filter
{
    /**
     * @var mixed
     */
    protected $_callback;

    /**
     * @param mixed $callback
     */
    public function __construct($callback)
    {
        $this->_callback = $callback;
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
        return call_user_func($this->_callback, $content);
    }
}
