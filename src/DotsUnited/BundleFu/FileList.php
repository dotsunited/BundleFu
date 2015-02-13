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

/**
 * DotsUnited\BundleFu\FileList
 *
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
class FileList implements \Iterator, \Countable
{
    /**
     * @var array
     */
    protected $files = array();

    /**
     * @var integer
     */
    protected $maxMTime = 0;

    /**
     * Add a file to the list.
     *
     * @param  string       $file     The file
     * @param  \SplFileInfo $fileInfo
     * @return FileList
     */
    public function addFile($file, $fileInfo)
    {
        if (!($fileInfo instanceof \SplFileInfo)) {
            $fileInfo = new \SplFileInfo($fileInfo);
        }

        $this->files[$file] = $fileInfo;

        try {
            $mTime = $fileInfo->getMTime();
        } catch (\Exception $e) {
            $mTime = 0;
        }

        if ($mTime > $this->maxMTime) {
            $this->maxMTime = $mTime;
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
        $this->files    = array();
        $this->maxMTime = 0;

        return $this;
    }

    /**
     * Get the maximum modification of all files in this list.
     *
     * @return integer
     */
    public function getMaxMTime()
    {
        return $this->maxMTime;
    }

    /**
     * Get a hash of this file list.
     *
     * @return string
     */
    public function getHash()
    {
        return md5(implode('', array_keys($this->files)));
    }

    /**
     * Implements Iterator::rewind()
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->files);
    }

    /**
     * Implements Iterator::current()
     *
     * @return \SplFileInfo
     */
    public function current()
    {
        if ($this->valid() === false) {
            // @codeCoverageIgnoreStart
            return null;
            // @codeCoverageIgnoreEnd
        }

        return current($this->files);
    }

    /**
     * Implements Iterator::key()
     *
     * @return string
     */
    public function key()
    {
        return key($this->files);
    }

    /**
     * Implements Iterator::next()
     *
     * @return void
     */
    public function next()
    {
        next($this->files);
    }

    /**
     * Implements Iterator::valid()
     *
     * @return boolean False if there's nothing more to iterate over
     */
    public function valid()
    {
        return current($this->files) !== false;
    }

    /**
     * Implements Countable::count()
     *
     * @return int
     */
    public function count()
    {
        return count($this->files);
    }
}
