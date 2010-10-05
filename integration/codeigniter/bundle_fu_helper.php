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
 * @subpackage Integration
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link http://www.opensource.org/licenses/bsd-license.php}
 */

/**
 * BundleFu Helper
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Integration
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link http://www.opensource.org/licenses/bsd-license.php}
 */
function bundle_fu()
{
    static $bundleFu;

    if (!$bundleFu) {
        // Setup autoloading
        spl_autoload_register(function($className) {
            if (strpos($className, 'Du\\') !== 0) {
                return;
            }
            require str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
            return true;
        });

        $bundleFu = new \Du\BundleFu\BundleFu();
        $bundleFu->setDocRoot(dirname(FCPATH));
    }

    return $bundleFu;
}
