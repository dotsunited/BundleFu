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
 * @subpackage UnitTests
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace Du\BundleFu;

/**
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage UnitTests
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link http://www.opensource.org/licenses/bsd-license.php}
 */
class OutputHandlerTest extends TestCase
{
    public function testInvokingObjectShouldRewriteHtml()
    {
        $handler = new OutputHandler($this->_bundleFu);
        $contents = $handler($this->_includeSome());

        $this->assertRegExp('/<link href="' . preg_quote($this->_bundleFu->getCssBundleUrl(), '/') . '[^"]*" rel="stylesheet" type="text\/css">/', $contents);
        $this->assertRegExp('/<script src="' . preg_quote($this->_bundleFu->getJsBundleUrl(), '/') . '[^"]*" type="text\/javascript">/', $contents);
    }
}
