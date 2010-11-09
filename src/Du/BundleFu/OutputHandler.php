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
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link http://www.opensource.org/licenses/bsd-license.php}
 */

namespace Du\BundleFu;

/**
 * Du\BundleFu\BundleFu
 *
 * @category   Du
 * @package    Du_BundleFu
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link http://www.opensource.org/licenses/bsd-license.php}
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
        $document = new \DOMDocument();
        $document->loadHTML($buffer);

        $bundleFu = $this->getBundleFu();
        $xpath    = new \DOMXPath($document);

        $nodes = $xpath->query('//script[@src]');

        if ($nodes->length > 0) {
            for ($i = 0, $length = $nodes->length; $i < $length; $i++) {
                $item = $nodes->item($i);
                $bundleFu->addJsFile($item->getAttribute('src'));

                if ($i >= $length - 1) {
                    $tmpDoc = new \DOMDocument();
                    $tmpDoc->loadHTML($bundleFu->renderJs());
                    $el = $document->importNode($tmpDoc->getElementsByTagName('script')->item(0));

                    $item->parentNode->replaceChild($el, $item);
                } else {
                    $item->parentNode->removeChild($item);
                }
            }
        }

        $nodes = $xpath->query('//link[@href]');

        if ($nodes->length > 0) {
            for ($i = 0, $length = $nodes->length; $i < $length; $i++) {
                $item = $nodes->item($i);
                $bundleFu->addCssFile($item->getAttribute('href'));

                if ($i >= $length - 1) {
                    $tmpDoc = new \DOMDocument();
                    $tmpDoc->loadHTML($bundleFu->renderCss());
                    $el = $document->importNode($tmpDoc->getElementsByTagName('link')->item(0));

                    $item->parentNode->replaceChild($el, $item);
                } else {
                    $item->parentNode->removeChild($item);
                }
            }
        }

        return $document->saveHtml();
    }
}
