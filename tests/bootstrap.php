<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2015 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (file_exists(__DIR__.'/../../../autoload.php')) {
    $loader = require __DIR__.'/../../../autoload.php';
} else {
    $loader = require __DIR__.'/../vendor/autoload.php';
}
$loader->add('DotsUnited\\BundleFu\\Tests', __DIR__);
