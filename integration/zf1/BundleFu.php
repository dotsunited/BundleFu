<?php

class Zend_View_Helper_BundleFu extends Zend_View_Helper_Abstract
{
    /**
     * @var \Du\BundleFu\BundleFu
     */
    protected $_bundleFu;

    /**
     * Set the BundleFu instance
     * 
     * @param \Du\BundleFu\BundleFu $bundleFu
     * @return Zend_View_Helper_BundleFu
     */
    public function setBundleFu(\Du\BundleFu\BundleFu $bundleFu)
    {
        $this->_bundleFu = $bundleFu;
        return $this;
    }

    /**
     * Get the BundleFu instance
     *
     * @return \Du\BundleFu\BundleFu
     */
    public function getBundleFu()
    {
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
        return call_user_func_array(array($this->getBundleFu(), $method), $params);
    }
}
