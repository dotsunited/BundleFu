<?php
/**
 * Du
 *
 * LICENSE
 *
 * This source file is subject to the BSD license that is available
 * through the world-wide-web at this URL:
 * http://opensource.org/licenses/bsd-license.php
 *
 * @category   Du
 * @package    Du_BundleFu
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace Du\BundleFu;

/**
 * Du\BundleFu\BundleFu
 *
 * @category   Du
 * @package    Du_BundleFu
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link http://www.opensource.org/licenses/bsd-license.php}
 * @version    Release: @package_version@
 */
class BundleFu
{
    /**
     * Whether bundling is disabled.
     *
     * @var bool
     */
    protected $_bypass = false;

    /**
     * Directory in which to look for files.
     *
     * @var string
     */
    protected $_docRoot;

    /**
     * Directory in which to write bundled css files.
     *
     * @var string
     */
    protected $_cssCachePath = 'css/cache';

    /**
     * Directory in which to write bundled javascript files.
     *
     * @var string
     */
    protected $_jsCachePath = 'js/cache';

    /**
     * Path the generated css bundles are publicly accessible under.
     *
     * Optional. If not set, $this->_cssCachePath is used.
     *
     * @var string
     */
    protected $_cssCacheUrl;

    /**
     * Path the generated javascript bundles are publicly accessible under.
     *
     * Optional. If not set, $this->_jsCachePath is used.
     *
     * @var string
     */
    protected $_jsCacheUrl;

    /**
     * Whether to render as XHTML.
     *
     * @var boolean
     */
    protected $_renderAsXhtml = false;

    /**
     * CSS file list.
     *
     * @var FileList
     */
    protected $_cssFileList;

    /**
     * Javascript file list.
     *
     * @var FileList
     */
    protected $_jsFileList;

    /**
     * CSS filter chain.
     *
     * @var Filter\FilterChan
     */
    protected $_cssFilterChain;

    /**
     * CSS filter chain.
     *
     * @var Filter\FilterChan
     */
    protected $_jsFilterChain;

    /**
     * CSS url rewriter.
     *
     * @var CssUrlRewriter
     */
    protected $_cssUrlRewriter;

    /**
     * Options for bundling in process.
     *
     * @var array
     */
    protected $_currentBundleOptions;

    /**
     * Set whether to bypass bundling
     *
     * @param boolean $bypass
     * @return BundleFu
     */
    public function setBypass($bypass)
    {
        $this->_bypass = $bypass;
        return $this;
    }

    /**
     * Get whether to bypass bundling
     *
     * @return boolean
     */
    public function getBypass()
    {
        return $this->_bypass ;
    }

    /**
     * Set directory in which to look for files
     *
     * @param string $docRoot
     * @return BundleFu
     */
    public function setDocRoot($docRoot)
    {
        $this->_docRoot = $docRoot;
        return $this;
    }

    /**
     * Get directory in which to look for files
     *
     * @return string
     */
    public function getDocRoot()
    {
        return $this->_docRoot ;
    }

    /**
     * Set directory in which to write bundled css files
     *
     * @param string $cssCachePath
     * @return BundleFu
     */
    public function setCssCachePath($cssCachePath)
    {
        $this->_cssCachePath = $cssCachePath;
        return $this;
    }

    /**
     * Get directory in which to write bundled css files
     *
     * @return string
     */
    public function getCssCachePath()
    {
        return $this->_cssCachePath ;
    }

    /**
     * Set directory in which to write bundled javascript files
     *
     * @param string $jsCachePath
     * @return BundleFu
     */
    public function setJsCachePath($jsCachePath)
    {
        $this->_jsCachePath = $jsCachePath;
        return $this;
    }

    /**
     * Get directory in which to write bundled javascript files
     *
     * @return string
     */
    public function getJsCachePath()
    {
        return $this->_jsCachePath ;
    }

    /**
     * Set path the generated css bundles are publicly accessible under
     *
     * @param string $cssCacheUrl
     * @return BundleFu
     */
    public function setCssCacheUrl($cssCacheUrl)
    {
        $this->_cssCacheUrl = $cssCacheUrl;
        return $this;
    }

    /**
     * Get path the generated css bundles are publicly accessible under
     *
     * @return string
     */
    public function getCssCacheUrl()
    {
        return $this->_cssCacheUrl ;
    }

    /**
     * Set path the generated javascript bundles are publicly accessible under
     *
     * @param string $jsCacheUrl
     * @return BundleFu
     */
    public function setJsCacheUrl($jsCacheUrl)
    {
        $this->_jsCacheUrl = $jsCacheUrl;
        return $this;
    }

    /**
     * Get path the generated javascript bundles are publicly accessible under
     *
     * @return string
     */
    public function getJsCacheUrl()
    {
        return $this->_jsCacheUrl ;
    }

    /**
     * Set whether to render as XHTML.
     *
     * @param  boolean $renderAsXhtml
     * @return BundleFu
     */
    public function setRenderAsXhtml($renderAsXhtml)
    {
        $this->_renderAsXhtml = $renderAsXhtml;
        return $this;
    }

    /**
     * Get whether to render as XHTML.
     *
     * @return boolean
     */
    public function getRenderAsXhtml()
    {
        return $this->_renderAsXhtml;
    }

    /**
     * Get css file list.
     *
     * @return FileList
     */
    public function getCssFileList()
    {
        if (null === $this->_cssFileList) {
            $this->_cssFileList = new FileList();
        }

        return $this->_cssFileList;
    }

    /**
     * Get javascript file list.
     *
     * @return FileList
     */
    public function getJsFileList()
    {
        if (null === $this->_jsFileList) {
            $this->_jsFileList = new FileList();
        }

        return $this->_jsFileList;
    }

    /**
     * Get css filter chain.
     *
     * @return FilterChain
     */
    public function getCssFilterChain()
    {
        if (null === $this->_cssFilterChain) {
            $this->_cssFilterChain = new Filter\FilterChain();
        }

        return $this->_cssFilterChain;
    }

    /**
     * Get javascript filter chain.
     *
     * @return FilterChain
     */
    public function getJsFilterChain()
    {
        if (null === $this->_jsFilterChain) {
            $this->_jsFilterChain = new Filter\FilterChain();
        }

        return $this->_jsFilterChain;
    }

    /**
     * Get css url rewriter.
     *
     * @return CssUrlRewriter
     */
    public function getCssUrlRewriter()
    {
        if (null === $this->_cssUrlRewriter) {
            $this->_cssUrlRewriter = new CssUrlRewriter();
        }

        return $this->_cssUrlRewriter;
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

        $hash = $this->getCssFileList()->getHash();

        return sprintf(
            "%s%sbundle_%s.css",
            $cacheDir,
            DIRECTORY_SEPARATOR,
            $hash
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

        return sprintf(
            "%s%sbundle_%s.js",
            $cacheDir,
            DIRECTORY_SEPARATOR,
            $this->getJsFileList()->getHash()
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

        return sprintf(
            "%s/bundle_%s.css",
            $url,
            $this->getCssFileList()->getHash()
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

        return sprintf(
            "%s/bundle_%s.js",
            $url,
            $this->getJsFileList()->getHash()
        );
    }

    /**
     * Add a CSS file.
     * 
     * @param string $file
     * @param string $docRoot
     * @return BundleFu 
     */
    public function addCssFile($file, $docRoot = null)
    {
        if (!$docRoot) {
            $docRoot = $this->getDocRoot();
        }

        $file    = preg_replace('/^https?:\/\/[^\/]+/i', '', $file);
        $abspath = $docRoot . $file;

        $this->getCssFileList()->addFile($file, $abspath);

        return $this;
    }

    /**
     * Add a javascript file.
     * 
     * @param string $file
     * @param string $docRoot
     * @return BundleFu 
     */
    public function addJsFile($file, $docRoot = null)
    {
        if (!$docRoot) {
            $docRoot = $this->getDocRoot();
        }

        $file    = preg_replace('/^https?:\/\/[^\/]+/i', '', $file);
        $abspath = $docRoot . $file;

        $this->getJsFileList()->addFile($file, $abspath);

        return $this;
    }

    /**
     * Start capturing and bundling current output.
     * 
     * @param array $options
     * @return BundleFu 
     */
    public function start(array $options = array())
    {
        $currentBundleOptions = array(
            'docroot' => $this->getDocRoot(),
            'bypass'  => $this->getBypass(),
        );

        $this->_currentBundleOptions = array_merge($currentBundleOptions, $options);
        ob_start();

        return $this;
    }

    /**
     * End capturing and bundling current output.
     * 
     * @param array $options
     * @return BundleFu 
     */
    public function end(array $options = array())
    {
        if (null === $this->_currentBundleOptions) {
            throw new \RuntimeException('end() is called without a start() call.');
        }

        $options = array_merge($this->_currentBundleOptions, $options);

        if (empty($options['docroot'])) {
            throw new \RuntimeException('Please set a document root either with setDocRoot() or via runtime through bundle options.');
        }

        $captured = ob_get_clean();

        if ($options['bypass']) {
            echo $captured;
        } else {
            preg_match_all('/(href|src) *= *["\']([^"^\'^\?]+)/i', $captured, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                if (strtolower($match[1]) == 'src') {
                    $this->addJsFile($match[2], $options['docroot']);
                } else {
                    $this->addCssFile($match[2], $options['docroot']);
                }
            }
        }

        $this->_currentBundleOptions = null;

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
            $return = $this->render();
            return $return;
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

        $cacheFile = $this->getCssBundlePath();
        $cacheTime = @filemtime($cacheFile);

        if (false === $cacheTime || $cacheTime < $cssFileList->getMaxMTime()) {
            $data = '';

            $cssUrlRewriter = $this->getCssUrlRewriter();

            foreach ($cssFileList as $file => $fileInfo) {
                $data .= '/* --------- ' . $file . ' --------- */' . PHP_EOL;
                $contents = @file_get_contents($fileInfo->getPathname());
                if (!$contents) {
                    $data .= '/* FILE READ ERROR! */' . PHP_EOL;
                } else {
                    $data .= $cssUrlRewriter->rewriteUrls($file, $contents) . PHP_EOL;
                }
            }

            $data = $this->getCssFilterChain()->filter($data);

            $dir = dirname($cacheFile);

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            if (false === file_put_contents($cacheFile, $data, LOCK_EX)) {
                throw new \RuntimeException('Cannot write css cache file to "' . $cacheFile . '"');
            }

            $cacheTime = filemtime($cacheFile);
        }

        return sprintf(
            '<link href="%s?%s" rel="stylesheet" type="text/css"%s>',
            $this->getCssBundleUrl(),
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

        $cacheFile = $this->getJsBundlePath();
        $cacheTime = @filemtime($cacheFile);

        if (false === $cacheTime || $cacheTime < $jsFileList->getMaxMTime()) {
            $data = '';

            foreach ($jsFileList as $file => $fileInfo) {
                $data .= '/* --------- ' . $file . ' --------- */' . PHP_EOL;
                $contents = @file_get_contents($fileInfo->getPathname());
                if (!$contents) {
                    $data .= '/* FILE READ ERROR! */' . PHP_EOL;
                } else {
                    $data .= $contents . PHP_EOL;
                }
            }

            $data = $this->getJsFilterChain()->filter($data);

            $dir = dirname($cacheFile);

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            if (false === file_put_contents($cacheFile, $data, LOCK_EX)) {
                throw new \RuntimeException('Cannot write js cache file to "' . $cacheFile . '"');
            }

            $cacheTime = filemtime($cacheFile);
        }

        return sprintf(
            '<script src="%s?%s" type="text/javascript"></script>',
            $this->getJsBundleUrl(),
            $cacheTime
        );
    }

    /**
     * Check whether $path is a local relative path.
     * 
     * @param string $path
     * @return boolean
     */
    public function isRelativePath($path)
    {
        return strpos($path, '://') === false && !preg_match('/^\\//', $path) && !preg_match('/^[A-Z]:\\\\/i', $path);
    }
}
