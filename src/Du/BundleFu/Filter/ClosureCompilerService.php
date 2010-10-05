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

namespace Du\BundleFu\Filter;

/**
 *  Du\BundleFu\Filter\ClosureCompilerService
 *
 * @category   Du
 * @package    Du_BundleFu
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    BSD License {@link http://www.opensource.org/licenses/bsd-license.php}
 * @version    Release: @package_version@
 */
class ClosureCompilerService implements Filter
{
    /**
     * @var array
     */
    protected $_parameters = array();

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = null)
    {
        if (null !== $parameters) {
            $this->_parameters = $parameters;
        }
    }

    /**
     * Returns $content filtered through each filter in the chain
     *
     * Filters are run in the order in which they were added to the chain (FIFO)
     *
     * @param mixed $content
     * @return mixed
     */
    public function filter($content)
    {
        $postdata = http_build_query(
            array(
                'js_code'       => $content,
                'output_format' => 'text',
                'output_info'   => 'compiled_code',
            ) + 
            $this->_parameters +
            array(
                'compilation_level' => 'SIMPLE_OPTIMIZATIONS'
            )
        );

        $opts = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context = stream_context_create($opts);

        $result = file_get_contents('http://closure-compiler.appspot.com/compile', false, $context);

        if (false !== $result && trim($result) !== '') {
            return $result;
        }

        return $content;
    }
}
