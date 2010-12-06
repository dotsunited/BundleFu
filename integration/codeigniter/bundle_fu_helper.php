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

/**
 * BundleFu Helper
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Integration
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 */
function bundle_fu()
{
    static $bundleFu;

    if (!$bundleFu) {
        // Setup autoloading
        spl_autoload_register(function($className) {
            if (strpos($className, 'Du\\BundleFu\\') === 0) {
                require str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
            }
        });

        $bundleFu = new \Du\BundleFu\BundleFu();
        $bundleFu->setDocRoot(dirname(FCPATH));
    }

    return $bundleFu;
}
