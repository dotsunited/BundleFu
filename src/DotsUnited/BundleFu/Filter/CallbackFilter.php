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
 *  DotsUnited\BundleFu\Filter\Callback
 *
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
class CallbackFilter implements FilterInterface
{
    /**
     * @var mixed
     */
    protected $callback;

    /**
     * @var mixed
     */
    protected $callbackFile;

    /**
     * Constructor.
     *
     * @param mixed $callback
     * @param mixed $callbackFile
     */
    public function __construct($callback = null, $callbackFile = null)
    {
        $this->callback     = $callback;
        $this->callbackFile = $callbackFile;
    }

    /**
     * {@inheritDoc}
     */
    public function filter($content)
    {
        if (null === $this->callback) {
            return $content;
        }

        return call_user_func($this->callback, $content);
    }

    /**
     * {@inheritDoc}
     */
    public function filterFile($content, $file, \SplFileInfo $fileInfo, $bundleUrl, $bundlePath)
    {
        if (null === $this->callbackFile) {
            return $content;
        }

        return call_user_func($this->callbackFile, $content, $file, $fileInfo, $bundleUrl, $bundlePath);
    }
}
