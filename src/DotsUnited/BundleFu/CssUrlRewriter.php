<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2011 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DotsUnited\BundleFu;

/**
 * DotsUnited\BundleFu\CssUrlRewriter
 *
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
class CssUrlRewriter
{
    /**
     * Rewrites relative urls in css files.
     *
     * @param string $filename
     * @param string $content
     * @return string
     */
    public function rewriteUrls($filename, $content)
    {
        $self = $this;
        return preg_replace_callback('/url *\(([^\)]+)\)/', function($matches) use ($self, $filename) {
            $relativeUrl = trim($matches[1], ' "\'');
            return 'url(' . $self->rewriteRelativePath($filename, $relativeUrl) . ')';
        }, $content);
    }

    /**
     * Rewrites relative urls depending on a base url.
     *
     * @param string $baseUrl
     * @param string $relativeUrl
     * @return string
     */
    public function rewriteRelativePath($baseUrl, $relativeUrl)
    {
        if ($relativeUrl[0] == '/' || strpos($relativeUrl, '://') !== false) {
            $absoluteUrl = $relativeUrl;
        } else {
            if (preg_match('/\.[a-z0-9]+$/i', $baseUrl)) {
                $baseUrl = dirname($baseUrl);
            }

            $path = trim($baseUrl, '/') . '/' . $relativeUrl;

            $parts     = array_filter(explode('/', $path));
            $absolutes = array();

            foreach ($parts as $part) {
                if ('.' == $part) {
                    continue;
                }

                if ('..' == $part) {
                    array_pop($absolutes);
                } else {
                    $absolutes[] = $part;
                }
            }

            $absoluteUrl = '/' . implode('/', $absolutes);
        }

        return $absoluteUrl;
    }
}
