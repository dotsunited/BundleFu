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
     * @var boolean
     */
    protected $rewriteRelative = false;

    /**
     * Set whether to try to rewrite urls relative.
     *
     * @param boolean $rewriteRelative
     * @return \DotsUnited\BundleFu\CssUrlRewriter
     */
    public function setRewriteRelative($rewriteRelative)
    {
        $this->rewriteRelative = (boolean) $rewriteRelative;

        return $this;
    }

    /**
     * Get whether to try to rewrite urls relative.
     *
     * @return boolean
     */
    public function getRewriteRelative()
    {
        return $this->rewriteRelative;
    }

    /**
     * Rewrites relative urls in css files.
     *
     * @param string $filename
     * @param string $content
     * @param string $destUrl
     * @return string
     */
    public function rewriteUrls($filename, $content, $destUrl = null)
    {
        $self = $this;
        return preg_replace_callback('/url *\(([^\)]+)\)/', function($matches) use ($self, $filename, $destUrl) {
            $relativeUrl = trim($matches[1], ' "\'');
            return 'url(' . $self->rewriteRelativePath($filename, $relativeUrl, $destUrl) . ')';
        }, $content);
    }

    /**
     * Rewrites relative urls depending on a base url.
     *
     * @param string $baseUrl
     * @param string $relativeUrl
     * @param string $destUrl
     * @return string
     */
    public function rewriteRelativePath($baseUrl, $relativeUrl, $destUrl = null)
    {
        if ($relativeUrl[0] == '/' || strpos($relativeUrl, '://') !== false) {
            return $relativeUrl;
        } else {
            if (preg_match('/\.[a-z0-9]+$/i', $baseUrl)) {
                $baseUrl = dirname($baseUrl);
            }

            if ($this->getRewriteRelative() && null !== $destUrl && strpos($destUrl, '://') === false) {
                if (preg_match('/\.[a-z0-9]+$/i', $destUrl)) {
                    $destUrl = dirname($destUrl);
                }

                return $this->rewriteRelative($baseUrl, $relativeUrl, $destUrl);
            }

            return $this->rewriteAbsolute($baseUrl, $relativeUrl);
        }
    }

    /**
     * Rewrites to an absolute url.
     *
     * @param string $baseUrl
     * @param string $relativeUrl
     * @return string
     */
    protected function rewriteAbsolute($baseUrl, $relativeUrl)
    {
        $path = trim($baseUrl, '/') . '/' . $relativeUrl;

        return '/' . $this->canonicalize($path);
    }

    /**
     * Rewrites to a relative url.
     *
     * @param string $baseUrl
     * @param string $relativeUrl
     * @param string $destUrl
     * @return string
     */
    protected function rewriteRelative($baseUrl, $relativeUrl, $destUrl)
    {
        $up   = str_repeat('../', count(explode('/', trim($destUrl, '/'))));
        $path = trim($baseUrl, '/') . '/' . $relativeUrl;

        return $up . $this->canonicalize($path);
    }

    /**
     * Canonicalize a path.
     *
     * @param string $path
     * @return string
     */
    protected function canonicalize($path)
    {
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

        return implode('/', $absolutes);
    }
}
