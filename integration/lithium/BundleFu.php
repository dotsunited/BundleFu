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
 * @subpackage Integration
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 */

namespace app\extensions\helper;

/**
 * li3_bundlefu\extensions\helper\BundleFu
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Integration
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 */
class BundleFu extends \lithium\template\Helper
{
    /**
     * @var \Du\BundleFu\BundleFu
     */
    protected $_bundleFu;

    /**
     * Setup autloading.
     *
     * @return void
     */
    protected function _init() {
        parent::_init();

        // Setup autoloading
        spl_autoload_register(function($className) {
            if (strpos($className, 'Du\\BundleFu\\') === 0) {
                require str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
            }
        });
    }

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
        if (null === $this->_bundleFu) {
            $this->_bundleFu = new \Du\BundleFu\BundleFu();
            $this->_bundleFu->setDocRoot($this->_context->request()->env('DOCUMENT_ROOT'));
        }

        return $this->_bundleFu;
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
}

?>