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
 * DotsUnited\BundleFu\Filter\CssUrlRewriteFilter
 *
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
class CssUrlRewriteFilter implements FilterInterface
{
    protected $file;
    protected $bundleUrl;

    /**
     * {@inheritDoc}
     */
    public function filter($content)
    {
        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function filterFile($content, $file, \SplFileInfo $fileInfo, $bundleUrl, $bundlePath)
    {
        $this->file = $file;
        $this->bundleUrl = $bundleUrl;

        $content = preg_replace_callback('/url\((["\']?)(?<url>.*?)(\\1)\)/', array($this, 'rewriteUrl'), $content);
        $content = preg_replace_callback('/@import (?!url\()(\'|"|)(?<url>[^\'"\)\n\r]*)\1;?/', array($this, 'rewriteUrl'), $content);
        // Handle 'src' values (used in e.g. calls to AlphaImageLoader, which is a proprietary IE filter)
        $content = preg_replace_callback('/\bsrc\s*=\s*(["\']?)(?<url>.*?)(\\1)/i', array($this, 'rewriteUrl'), $content);

        return $content;
    }

    /**
     * Callback which rewrites matched CSS ruls.
     *
     * @param  array  $matches
     * @return string
     */
    protected function rewriteUrl($matches)
    {
        $matchedUrl = trim($matches['url']);

        // First check also matches protocol-relative urls like //example.com/images/bg.gif
        if ('/' === $matchedUrl[0] || false !== strpos($matchedUrl, '://') || 0 === strpos($matchedUrl, 'data:')) {
            return $matches[0];
        }

        $sourceUrl = dirname($this->file);

        if ('.' === $sourceUrl) {
            $sourceUrl = '/';
        }

        $path = $this->bundleUrl;

        if (false !== strpos($path, '://') || 0 === strpos($path, '//')) {
            // parse_url() does not work with protocol-relative urls
            list(, $url) = explode('//', $path, 2);
            list(, $path) = explode('/', $url, 2);
        }

        $bundleUrl = dirname($path);

        if ('.' === $bundleUrl) {
            $bundleUrl = '/';
        }

        $url = $this->rewriteRelative($matchedUrl, $sourceUrl, $bundleUrl);

        return str_replace($matchedUrl, $url, $matches[0]);
    }

    /**
     * Rewrites to a relative url.
     *
     * @param  string $url
     * @param  string $sourceUrl
     * @param  string $bundleUrl
     * @return string
     */
    protected function rewriteRelative($url, $sourceUrl, $bundleUrl)
    {
        $sourceUrl = trim($sourceUrl, '/');
        $bundleUrl = trim($bundleUrl, '/');

        if ($bundleUrl === $sourceUrl) {
            return $url;
        }

        if ('' === $sourceUrl) {
            return str_repeat('../', count(explode('/', $bundleUrl))) . $url;
        }

        if ('' === $bundleUrl) {
            return $this->canonicalize($sourceUrl . '/' . $url);
        }

        if (0 === strpos($bundleUrl, $sourceUrl)) {
            $prepend = $bundleUrl;
            $count = 0;

            while ($prepend !== $sourceUrl) {
                $count++;
                $prepend = dirname($prepend);
            }

            return str_repeat('../', $count) . $url;
        } elseif (0 === strpos($sourceUrl, $bundleUrl)) {
            $path = $sourceUrl;
            while (0 === strpos($url, '../') && $path !== $bundleUrl) {
                $path = dirname($path);
                $url = substr($url, 3);
            }

            return $url;
        } else {
            $prepend = str_repeat('../', count(explode('/', $bundleUrl)));
            $path = $sourceUrl . '/' . $url;

            return $prepend . $this->canonicalize($path);
        }
    }

    /**
     * Canonicalizes a path.
     *
     * @param  string $path
     * @return string
     */
    protected function canonicalize($path)
    {
        $parts = array_filter(explode('/', $path));
        $canonicalized = array();

        foreach ($parts as $part) {
            if ('.' == $part) {
                continue;
            }

            if ('..' == $part) {
                array_pop($canonicalized);
            } else {
                $canonicalized[] = $part;
            }
        }

        return implode('/', $canonicalized);
    }
}
