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

namespace Du\BundleFu;

/**
 * Du\BundleFu\BundleFu
 *
 * @category   Du
 * @package    Du_BundleFu
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE}
 * @version    Release: @package_version@
 */
class OutputHandler
{
    /**
     * The BundleFu instance.
     *
     * @var BundleFu
     */
    protected $_bundleFu;

    /**
     *
     * @param BundleFu $bundleFu 
     */
    public function __construct(BundleFu $bundleFu)
    {
        $this->setBundleFu($bundleFu);
    }

    /**
     *
     * @param BundleFu $bundleFu
     * @return OutputHandler
     */
    public function setBundleFu(BundleFu $bundleFu)
    {
        $this->_bundleFu = $bundleFu;
        return $this;
    }

    /**
     *
     * @return BundleFu
     */
    public function getBundleFu()
    {
        return $this->_bundleFu;
    }

    /**
     * Invoke the object as callback.
     *
     * Intendend as a ob_start() output callback.
     *
     * @param string $buffer
     * @return string
     */
    public function __invoke($buffer)
    {
        $bundleFu = $this->getBundleFu();

        preg_match_all("/(<link[^>]+>)+/i", $buffer, $matches, PREG_SET_ORDER);

        $last = count($matches) - 1;
        $curr = 0;
        foreach ($matches as $match) {
            $bundleFu->extractFiles($match[0]);

            if ($curr++ == $last) {
                $replace = $bundleFu->renderCss() . PHP_EOL;
            } else {
                $replace = '';
            }

            $buffer = preg_replace('/' . preg_quote($match[0], '/') . '[\r|\n|\r\n]*/', $replace, $buffer);
        }

        preg_match_all("/(<script[^>]+><\/script>)+/siU", $buffer, $matches, PREG_SET_ORDER);

        $last = count($matches) - 1;
        $curr = 0;
        foreach ($matches as $match) {
            $bundleFu->extractFiles($match[0]);

            if ($curr++ == $last) {
                $replace = $bundleFu->renderJs() . PHP_EOL;
            } else {
                $replace = '';
            }

            $buffer = preg_replace('/' . preg_quote($match[0], '/') . '[\r|\n|\r\n]*/', $replace, $buffer);
        }

        return $buffer;
    }
}
