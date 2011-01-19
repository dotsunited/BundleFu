<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2011 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * BundleFu Helper
 *
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 */
function bundle_fu()
{
    static $bundleFu;

    if (!$bundleFu) {
        // Setup autoloading
        spl_autoload_register(function($className) {
            if (strpos($className, 'DotsUnited\\BundleFu\\') === 0) {
                require str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
            }
        });

        $bundleFu = new \DotsUnited\BundleFu\BundleFu();
        $bundleFu->setDocRoot(dirname(FCPATH));
    }

    return $bundleFu;
}
