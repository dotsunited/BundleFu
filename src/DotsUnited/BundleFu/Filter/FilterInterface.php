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
 * DotsUnited\BundleFu\Filter\FilterInterface
 *
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
interface FilterInterface
{
    /**
     * Filter applied to concenated content before its written to the cache file.
     *
     * @param  mixed $content
     * @return mixed
     */
    public function filter($content);

    /**
     * Filter applied to a single file after it has beed loaded.
     *
     * @param  mixed        $content
     * @param  string       $file       File as it appears in the href/src attribute
     * @param  \SplFileInfo $fileInfo
     * @param  string       $bundleUrl  The url of the bundle this file will be added to
     * @param  string       $bundlePath The path of the bundle this file will be added to
     * @return mixed
     */
    public function filterFile($content, $file, \SplFileInfo $fileInfo, $bundleUrl, $bundlePath);
}
