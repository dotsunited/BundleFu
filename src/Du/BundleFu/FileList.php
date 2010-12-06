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

namespace Du\BundleFu;

/**
 * Du\BundleFu\FileList
 *
 * @category   Du
 * @package    Du_BundleFu
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 * @version    Release: @package_version@
 */
class FileList implements \Iterator, \Countable
{
    /**
     * @var array
     */
    protected $_files = array();

    /**
     * @var integer
     */
    protected $_maxMTime = 0;

    /**
     * Add a file to the list.
     *
     * @param string $file The (relative) file
     * @param \SplFileInfo $fileInfo
     * @return FileList
     */
    public function addFile($file, $fileInfo)
    {
        if (!($fileInfo instanceof \SplFileInfo)) {
            $fileInfo = new \SplFileInfo($fileInfo);
        }

        $this->_files[$file] = $fileInfo;

        try {
            $mTime = $fileInfo->getMTime();
        } catch (\Exception $e) {
            $mTime = 0;
        }

        if ($mTime > $this->_maxMTime) {
            $this->_maxMTime = $mTime;
        }

        return $this;
    }

    /**
     * Reset the file list.
     *
     * @return FileList
     */
    public function reset()
    {
        $this->_files    = array();
        $this->_maxMTime = 0;

        return $this;
    }

    /**
     * Get the maximum modification of all files in this list.
     * 
     * @return integer
     */
    public function getMaxMTime()
    {
        return $this->_maxMTime;
    }

    /**
     * Get a hash of this file list.
     * 
     * @return string
     */
    public function getHash()
    {
        return md5(implode('', array_keys($this->_files)));
    }

    /**
     * Implements Iterator::rewind()
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->_files);
    }

    /**
     * Implements Iterator::current()
     *
     * @return \SplFileInfo
     */
    public function current()
    {
        if ($this->valid() === false) {
            return null;
        }

        return current($this->_files);
    }

    /**
     * Implements Iterator::key()
     *
     * @return string
     */
    public function key()
    {
        return key($this->_files);
    }

    /**
     * Implements Iterator::next()
     *
     * @return void
     */
    public function next()
    {
        next($this->_files);
    }

    /**
     * Implements Iterator::valid()
     *
     * @return boolean False if there's nothing more to iterate over
     */
    public function valid()
    {
        return current($this->_files) !== false;
    }

    /**
     * Implements Countable::count()
     *
     * @return int
     */
    public function count()
    {
        return count($this->_files);
    }
}
