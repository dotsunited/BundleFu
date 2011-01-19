<?php
/**
 * DotsUnited\BundleFu
 *
 * LICENSE
 *
 * This source file is subject to the BSD license that is available
 * through the world-wide-web at this URL:
 * https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Filter
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 */

namespace DotsUnited\BundleFu\Filter;

/**
 *  DotsUnited\BundleFu\Filter\ClosureCompilerService
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Filter
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 * @version    @package_version@
 */
class ClosureCompilerService implements FilterInterface
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