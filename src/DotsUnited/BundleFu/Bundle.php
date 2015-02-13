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

use DotsUnited\BundleFu\Filter\FilterInterface;

/**
 * DotsUnited\BundleFu\Bundle
 *
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
class Bundle
{
    /**
     * Whether to bypass capturing.
     *
     * @var boolean
     */
    protected $bypass = false;

    /**
     * Whether to force bundling.
     *
     * @var boolean
     */
    protected $force = false;

    /**
     * Directory in which to look for files.
     *
     * @var string
     */
    protected $docRoot;

    /**
     * Bundle name.
     *
     * @var string
     */
    protected $name;

    /**
     * Directory in which to write bundled css files.
     *
     * @var string
     */
    protected $cssCachePath = 'css/cache';

    /**
     * Directory in which to write bundled javascript files.
     *
     * @var string
     */
    protected $jsCachePath = 'js/cache';

    /**
     * Path the generated css bundles are publicly accessible under.
     *
     * Optional. If not set, $this->cssCachePath is used.
     *
     * @var string
     */
    protected $cssCacheUrl;

    /**
     * Path the generated javascript bundles are publicly accessible under.
     *
     * Optional. If not set, $this->jsCachePath is used.
     *
     * @var string
     */
    protected $jsCacheUrl;

    /**
     * Whether to render as XHTML.
     *
     * @var boolean
     */
    protected $renderAsXhtml = false;

    /**
     * CSS file list.
     *
     * @var FileList
     */
    protected $cssFileList;

    /**
     * Javascript file list.
     *
     * @var FileList
     */
    protected $jsFileList;

    /**
     * CSS filter.
     *
     * @var Filter
     */
    protected $cssFilter;

    /**
     * CSS filter.
     *
     * @var Filter
     */
    protected $jsFilter;

    /**
     * CSS template.
     *
     * @var string|callable
     */
    protected $cssTemplate = '<link href="%s?%s" rel="stylesheet" type="text/css"%s>';

    /**
     * CSS filter.
     *
     * @var Filter
     */
    protected $jsTemplate = '<script src="%s?%s" type="text/javascript"></script>';

    /**
     * Options for bundling in process.
     *
     * @var array
     */
    protected $currentBundleOptions;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }

    /**
     * Allows to pass options as array.
     *
     * @param  array  $options
     * @return Bundle
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $val) {
            switch ($key) {
                case 'name':
                    $this->setName($val);
                    break;
                case 'doc_root':
                    $this->setDocRoot($val);
                    break;
                case 'bypass':
                    $this->setBypass($val);
                    break;
                case 'force':
                    $this->setForce($val);
                    break;
                case 'render_as_xhtml':
                    $this->setRenderAsXhtml($val);
                    break;
                case 'css_filter':
                    $this->setCssFilter($val);
                    break;
                case 'js_filter':
                    $this->setJsFilter($val);
                    break;
                case 'css_cache_path':
                    $this->setCssCachePath($val);
                    break;
                case 'js_cache_path':
                    $this->setJsCachePath($val);
                    break;
                case 'css_cache_url':
                    $this->setCssCacheUrl($val);
                    break;
                case 'js_cache_url':
                    $this->setJsCacheUrl($val);
                    break;
                case 'css_template':
                    $this->setCssTemplate($val);
                    break;
                case 'js_template':
                    $this->setJsTemplate($val);
                    break;
            }
        }

        return $this;
    }

    /**
     * Set whether to bypass capturing.
     *
     * @param  boolean $bypass
     * @return Bundle
     */
    public function setBypass($bypass)
    {
        $this->bypass = $bypass;

        return $this;
    }

    /**
     * Get whether to bypass capturing.
     *
     * @return boolean
     */
    public function getBypass()
    {
        return $this->bypass;
    }

    /**
     * Set whether to force bundling.
     *
     * @param  boolean $force
     * @return Bundle
     */
    public function setForce($force)
    {
        $this->force = $force;

        return $this;
    }

    /**
     * Get whether to force bundling.
     *
     * @return boolean
     */
    public function getForce()
    {
        return $this->force;
    }

    /**
     * Set directory in which to look for files.
     *
     * @param  string $docRoot
     * @return Bundle
     */
    public function setDocRoot($docRoot)
    {
        $this->docRoot = $docRoot;

        return $this;
    }

    /**
     * Get directory in which to look for files.
     *
     * @return string
     */
    public function getDocRoot()
    {
        return $this->docRoot;
    }

    /**
     * Set the bundle name.
     *
     * @param  string $name
     * @return Bundle
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the bundle name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set directory in which to write bundled css files.
     *
     * @param  string $cssCachePath
     * @return Bundle
     */
    public function setCssCachePath($cssCachePath)
    {
        $this->cssCachePath = $cssCachePath;

        return $this;
    }

    /**
     * Get directory in which to write bundled css files.
     *
     * @return string
     */
    public function getCssCachePath()
    {
        return $this->cssCachePath;
    }

    /**
     * Set directory in which to write bundled javascript files.
     *
     * @param  string $jsCachePath
     * @return Bundle
     */
    public function setJsCachePath($jsCachePath)
    {
        $this->jsCachePath = $jsCachePath;

        return $this;
    }

    /**
     * Get directory in which to write bundled javascript files.
     *
     * @return string
     */
    public function getJsCachePath()
    {
        return $this->jsCachePath;
    }

    /**
     * Set path the generated css bundles are publicly accessible under.
     *
     * @param  string $cssCacheUrl
     * @return Bundle
     */
    public function setCssCacheUrl($cssCacheUrl)
    {
        $this->cssCacheUrl = $cssCacheUrl;

        return $this;
    }

    /**
     * Get path the generated css bundles are publicly accessible under.
     *
     * @return string
     */
    public function getCssCacheUrl()
    {
        return $this->cssCacheUrl ;
    }

    /**
     * Set path the generated javascript bundles are publicly accessible under.
     *
     * @param  string $jsCacheUrl
     * @return Bundle
     */
    public function setJsCacheUrl($jsCacheUrl)
    {
        $this->jsCacheUrl = $jsCacheUrl;

        return $this;
    }

    /**
     * Get path the generated javascript bundles are publicly accessible under.
     *
     * @return string
     */
    public function getJsCacheUrl()
    {
        return $this->jsCacheUrl ;
    }

    /**
     * Set whether to render as XHTML.
     *
     * @param  boolean $renderAsXhtml
     * @return Bundle
     */
    public function setRenderAsXhtml($renderAsXhtml)
    {
        $this->renderAsXhtml = $renderAsXhtml;

        return $this;
    }

    /**
     * Get whether to render as XHTML.
     *
     * @return boolean
     */
    public function getRenderAsXhtml()
    {
        return $this->renderAsXhtml;
    }

    /**
     * Set the template used for rendering the css <link> tag (can be a callable).
     *
     * @param  string|callable $cssTemplate
     * @return Bundle
     */
    public function setCssTemplate($cssTemplate)
    {
        $this->cssTemplate = $cssTemplate;

        return $this;
    }

    /**
     * Get the template used for rendering the css <link> tag (can be a callable).
     *
     * @return string|callable
     */
    public function getCssTemplate()
    {
        return $this->cssTemplate ;
    }

    /**
     * Set the template used for rendering the js <script> tag (can be a callable).
     *
     * @param  string|callable $jsTemplate
     * @return Bundle
     */
    public function setJsTemplate($jsTemplate)
    {
        $this->jsTemplate = $jsTemplate;

        return $this;
    }

    /**
     * Get the template used for rendering the js <script> tag (can be a callable).
     *
     * @return string|callable
     */
    public function getJsTemplate()
    {
        return $this->jsTemplate ;
    }

    /**
     * Get css file list.
     *
     * @return FileList
     */
    public function getCssFileList()
    {
        if (null === $this->cssFileList) {
            $this->cssFileList = new FileList();
        }

        return $this->cssFileList;
    }

    /**
     * Get javascript file list.
     *
     * @return FileList
     */
    public function getJsFileList()
    {
        if (null === $this->jsFileList) {
            $this->jsFileList = new FileList();
        }

        return $this->jsFileList;
    }

    /**
     * Set css filter.
     *
     * @param FilterInterface
     * @return Bundle
     */
    public function setCssFilter(FilterInterface $filter = null)
    {
        $this->cssFilter = $filter;

        return $this;
    }

    /**
     * Get css filter.
     *
     * @return FilterInterface
     */
    public function getCssFilter()
    {
        return $this->cssFilter;
    }

    /**
     * Set javascript filter.
     *
     * @param FilterInterface
     * @return Bundle
     */
    public function setJsFilter(FilterInterface $filter = null)
    {
        $this->jsFilter = $filter;

        return $this;
    }

    /**
     * Get javascript filter.
     *
     * @return FilterInterface
     */
    public function getJsFilter()
    {
        return $this->jsFilter;
    }

    /**
     * Get css bundle path.
     *
     * @return string
     */
    public function getCssBundlePath()
    {
        $cacheDir = $this->getCssCachePath();

        if ($this->isRelativePath($cacheDir)) {
            $cacheDir = $this->getDocRoot() . DIRECTORY_SEPARATOR . $cacheDir;
        }

        $name = $this->getName();

        if (null === $name) {
            $name = sprintf('bundle_%s', $this->getCssFileList()->getHash());
        } elseif (strpos($name, '%s') !== false) {
            $name = sprintf($name, $this->getCssFileList()->getHash());
        }

        return sprintf(
            "%s%s%s.css",
            $cacheDir,
            DIRECTORY_SEPARATOR,
            $name
        );
    }

    /**
     * Get javascript bundle path.
     *
     * @return string
     */
    public function getJsBundlePath()
    {
        $cacheDir = $this->getJsCachePath();

        if ($this->isRelativePath($cacheDir)) {
            $cacheDir = $this->getDocRoot() . DIRECTORY_SEPARATOR . $cacheDir;
        }

        $name = $this->getName();

        if (null === $name) {
            $name = sprintf('bundle_%s', $this->getJsFileList()->getHash());
        } elseif (strpos($name, '%s') !== false) {
            $name = sprintf($name, $this->getJsFileList()->getHash());
        }

        return sprintf(
            "%s%s%s.js",
            $cacheDir,
            DIRECTORY_SEPARATOR,
            $name
        );
    }

    /**
     * Get css bundle url.
     *
     * @return string
     */
    public function getCssBundleUrl()
    {
        $url = $this->getCssCacheUrl();

        if (!$url) {
            $url = $this->getCssCachePath();

            if (!$this->isRelativePath($url)) {
                throw new \RuntimeException('If you do not provide a css cache url, css cache path must be a relative local path...');
            }

            $url = '/' . str_replace(DIRECTORY_SEPARATOR, '/', $url);
        }

        $name = $this->getName();

        if (null === $name) {
            $name = sprintf('bundle_%s', $this->getCssFileList()->getHash());
        } elseif (strpos($name, '%s') !== false) {
            $name = sprintf($name, $this->getCssFileList()->getHash());
        }

        return sprintf(
            "%s/%s.css",
            $url,
            $name
        );
    }

    /**
     * Get javascript bundle url.
     *
     * @return string
     */
    public function getJsBundleUrl()
    {
        $url = $this->getJsCacheUrl();

        if (!$url) {
            $url = $this->getJsCachePath();

            if (!$this->isRelativePath($url)) {
                throw new \RuntimeException('If you do not provide a js cache url, js cache path must be a relative local path...');
            }

            $url = '/' . str_replace(DIRECTORY_SEPARATOR, '/', $url);
        }

        $name = $this->getName();

        if (null === $name) {
            $name = sprintf('bundle_%s', $this->getJsFileList()->getHash());
        } elseif (strpos($name, '%s') !== false) {
            $name = sprintf($name, $this->getJsFileList()->getHash());
        }

        return sprintf(
            "%s/%s.js",
            $url,
            $name
        );
    }

    /**
     * Add a CSS file.
     *
     * @param  string $file
     * @param  string $docRoot
     * @return Bundle
     */
    public function addCssFile($file, $docRoot = null)
    {
        if (null === $docRoot) {
            $docRoot = $this->getDocRoot();
        }

        $docRoot = (string) $docRoot;

        if ('' !== $docRoot) {
            $docRoot .= DIRECTORY_SEPARATOR;
        }

        $file    = preg_replace('/^https?:\/\/[^\/]+/i', '', $file);
        $abspath = $docRoot . $file;

        $this->getCssFileList()->addFile($file, $abspath);

        return $this;
    }

    /**
     * Add a javascript file.
     *
     * @param  string $file
     * @param  string $docRoot
     * @return Bundle
     */
    public function addJsFile($file, $docRoot = null)
    {
        if (null === $docRoot) {
            $docRoot = $this->getDocRoot();
        }

        $docRoot = (string) $docRoot;

        if ('' !== $docRoot) {
            $docRoot .= DIRECTORY_SEPARATOR;
        }

        $file    = preg_replace('/^https?:\/\/[^\/]+/i', '', $file);
        $abspath = $docRoot . $file;

        $this->getJsFileList()->addFile($file, $abspath);

        return $this;
    }

    /**
     * Start capturing and bundling current output.
     *
     * @param  array  $options
     * @return Bundle
     */
    public function start(array $options = array())
    {
        $currentBundleOptions = array(
            'docroot' => $this->getDocRoot(),
            'bypass'  => $this->getBypass()
        );

        $this->currentBundleOptions = array_merge($currentBundleOptions, $options);
        ob_start();

        return $this;
    }

    /**
     * End capturing and bundling current output.
     *
     * @param  array  $options
     * @return Bundle
     */
    public function end(array $options = array())
    {
        if (null === $this->currentBundleOptions) {
            throw new \RuntimeException('end() is called without a start() call.');
        }

        $options = array_merge($this->currentBundleOptions, $options);

        $captured = ob_get_clean();

        if ($options['bypass']) {
            echo $captured;
        } else {
            $this->extractFiles($captured, $options['docroot']);
        }

        $this->currentBundleOptions = null;

        return $this;
    }

    /**
     * Extract files from HTML.
     *
     * @param  string $html
     * @param  string $docRoot
     * @return Bundle
     */
    public function extractFiles($html, $docRoot = null)
    {
        if (null === $docRoot) {
            $docRoot = $this->getDocRoot();
        }

        preg_match_all('/(href|src) *= *["\']([^"^\'^\?]+)/i', $html, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if (strtolower($match[1]) == 'src') {
                $this->addJsFile($match[2], $docRoot);
            } else {
                $this->addCssFile($match[2], $docRoot);
            }
        }

        return $this;
    }

    /**
     * Reset the bundle.
     *
     * @return Bundle
     */
    public function reset()
    {
        $this->getCssFileList()->reset();
        $this->getJsFileList()->reset();

        return $this;
    }

    /**
     * Render out all bundles.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);

            return '';
        }
    }

    /**
     * Render out all bundle.
     *
     * @return string
     */
    public function render()
    {
        return trim($this->renderCss() . PHP_EOL . $this->renderJs());
    }

    /**
     * Render out the css bundle.
     *
     * @return string
     */
    public function renderCss()
    {
        $cssFileList = $this->getCssFileList();

        if ($cssFileList->count() == 0) {
            return '';
        }

        $generate   = true;
        $bundlePath = $this->getCssBundlePath();

        if (!$this->getForce() && file_exists($bundlePath)) {
            $cacheTime = filemtime($bundlePath);

            if (false !== $cacheTime && $cacheTime >= $cssFileList->getMaxMTime()) {
                $generate = false;
            }
        }

        $bundleUrl = $this->getCssBundleUrl();

        if ($generate) {
            $data   = '';
            $filter = $this->getCssFilter();

            foreach ($cssFileList as $file => $fileInfo) {
                $data .= '/* --------- ' . $file . ' --------- */' . PHP_EOL;
                $contents = @file_get_contents($fileInfo->getPathname());
                if (!$contents) {
                    $data .= '/* FILE READ ERROR! */' . PHP_EOL;
                } else {
                    if (null !== $filter) {
                        $contents = $filter->filterFile($contents, $file, $fileInfo, $bundleUrl, $bundlePath);
                    }

                    $data .= $contents . PHP_EOL;
                }
            }

            if (null !== $filter) {
                $data = $filter->filter($data);
            }

            $cacheTime = $this->writeBundleFile($bundlePath, $data);
        }

        $template = $this->getCssTemplate();

        if (is_callable($template)) {
            return call_user_func($template, $bundleUrl, $cacheTime, $this->getRenderAsXhtml());
        }

        return sprintf(
            $template,
            $bundleUrl,
            $cacheTime,
            $this->getRenderAsXhtml() ? ' /' : ''
        );
    }

    /**
     * Render out the javascript bundle.
     *
     * @return string
     */
    public function renderJs()
    {
        $jsFileList = $this->getJsFileList();

        if ($jsFileList->count() == 0) {
            return '';
        }

        $generate   = true;
        $bundlePath = $this->getJsBundlePath();

        if (!$this->getForce() && file_exists($bundlePath)) {
            $cacheTime = filemtime($bundlePath);

            if (false !== $cacheTime && $cacheTime >= $jsFileList->getMaxMTime()) {
                $generate = false;
            }
        }

        $bundleUrl = $this->getJsBundleUrl();

        if ($generate) {
            $data   = '';
            $filter = $this->getJsFilter();

            foreach ($jsFileList as $file => $fileInfo) {
                $data .= '/* --------- ' . $file . ' --------- */' . PHP_EOL;
                $contents = @file_get_contents($fileInfo->getPathname());
                if (!$contents) {
                    $data .= '/* FILE READ ERROR! */' . PHP_EOL;
                } else {
                    if (null !== $filter) {
                        $contents = $filter->filterFile($contents, $file, $fileInfo, $bundleUrl, $bundlePath);
                    }

                    $data .= $contents . PHP_EOL;
                }
            }

            if (null !== $filter) {
                $data = $filter->filter($data);
            }

            $cacheTime = $this->writeBundleFile($bundlePath, $data);
        }

        $template = $this->getJsTemplate();

        if (is_callable($template)) {
            return call_user_func($template, $bundleUrl, $cacheTime);
        }

        return sprintf(
            $template,
            $bundleUrl,
            $cacheTime
        );
    }

    /**
     * Check whether $path is a local relative path.
     *
     * @param  string  $path
     * @return boolean
     */
    public function isRelativePath($path)
    {
        return strpos($path, '://') === false && !preg_match('/^\\//', $path) && !preg_match('/^[A-Z]:\\\\/i', $path);
    }

    /**
     * Write a bundle file to disk.
     *
     * @param  string  $bundlePath
     * @param  string  $data
     * @return integer
     */
    protected function writeBundleFile($bundlePath, $data)
    {
        $dir = dirname($bundlePath);

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        if (false === file_put_contents($bundlePath, $data, LOCK_EX)) {
            // @codeCoverageIgnoreStart
            throw new \RuntimeException('Cannot write cache file to "' . $bundlePath . '"');
            // @codeCoverageIgnoreEnd
        }

        return filemtime($bundlePath);
    }
}
