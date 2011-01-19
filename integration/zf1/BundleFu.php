<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2011 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/** Zend_View_Helper_Abstract.php */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Zend_View_Helper_BundleFu
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Integration
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 */
class Zend_View_Helper_BundleFu extends Zend_View_Helper_Abstract
{
    /**
     * @var \DotsUnited\BundleFu\BundleFu
     */
    protected $_bundleFu;

    /**
     * Set the BundleFu instance
     *
     * @param \DotsUnited\BundleFu\BundleFu $bundleFu
     * @return Zend_View_Helper_BundleFu
     */
    public function setBundleFu(\DotsUnited\BundleFu\BundleFu $bundleFu)
    {
        $this->_bundleFu = $bundleFu;
        return $this;
    }

    /**
     * Get the BundleFu instance
     *
     * @return \DotsUnited\BundleFu\BundleFu
     */
    public function getBundleFu()
    {
        if (null === $this->_bundleFu) {
            $this->_bundleFu = new \DotsUnited\BundleFu\BundleFu();
        }

        return $this->_bundleFu;
    }

    /**
     * @return Zend_View_Helper_BundleFu
     */
    public function bundleFu()
    {
        return $this;
    }

    /**
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        $return = call_user_func_array(array($this->getBundleFu(), $method), $params);

        switch ($method) {
            case 'start':
            case 'end':
            case substr($method, 0, 3) == 'set':
                return $this;
            default:
                return $return;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            $return = $this->getBundleFu()->render();
            return $return;
        } catch (\Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
            return '';
        }
    }

    /**
     * @param Zend_View_Helper_HeadLink $helper
     * @return Zend_View_Helper_BundleFu
     */
    public function bundleHeadLink(Zend_View_Helper_HeadLink $helper)
    {
        $bundleFu = $this->getBundleFu();

        foreach ($helper->getArrayCopy() as $key => $item) {
            if (isset($item->href)) {
                $bundleFu->getCssFileList()->addFile($item->href, $bundleFu->getDocRoot() . $item->href);
                unset($helper[$key]);
            }
        }

        return $this;
    }

    /**
     * @param Zend_View_Helper_HeadScript $helper
     * @return Zend_View_Helper_BundleFu
     */
    public function bundleHeadScript(Zend_View_Helper_HeadScript $helper)
    {
        $bundleFu = $this->getBundleFu();

        foreach ($helper->getArrayCopy() as $key => $item) {
            if (isset($item->attributes['src'])) {
                $bundleFu->getJsFileList()->addFile($item->attributes['src'], $bundleFu->getDocRoot() . $item->attributes['src']);
                unset($helper[$key]);
            }
        }

        return $this;
    }
}
