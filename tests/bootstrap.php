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
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 */

// Get base and application path
$rootPath = dirname(__DIR__);

set_include_path(implode(PATH_SEPARATOR, array(
    $rootPath . '/tests',
    $rootPath . '/src',
    get_include_path()
)));

/**
 * Setup autoloading
 */
spl_autoload_register(function($className) {
    if (false !== strripos($className, '\\')) {
        $replace = '\\';
    } else {
        $replace = '_';
    }

    require str_replace($replace, DIRECTORY_SEPARATOR, $className) . '.php';
}, true, true);

// Define filters for clover report
PHP_CodeCoverage_Filter::getInstance()->addDirectoryToWhitelist($rootPath . '/src');

PHP_CodeCoverage_Filter::getInstance()->addDirectoryToBlacklist($rootPath . '/tests');

if (defined('PEAR_INSTALL_DIR') && is_dir(PEAR_INSTALL_DIR)) {
    PHP_CodeCoverage_Filter::getInstance()->addDirectoryToBlacklist(PEAR_INSTALL_DIR);
}
if (defined('PHP_LIBDIR') && is_dir(PEAR_INSTALL_DIR)) {
    PHP_CodeCoverage_Filter::getInstance()->addDirectoryToBlacklist(PHP_LIBDIR);
}

unset($rootPath);
