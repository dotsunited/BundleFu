<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2015 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DotsUnited\BundleFu\Filter;

/**
 *  DotsUnited\BundleFu\Filter\ClosureCompilerService
 *
 * @author  Jan Sorgalla <jan.sorgalla@dotsunited.de>
 * @version @package_version@
 */
class ClosureCompilerServiceFilter implements FilterInterface
{
    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * Constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = null)
    {
        if (null !== $parameters) {
            $this->parameters = $parameters;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function filter($content)
    {
        $postdata = http_build_query(
            array(
                'js_code'       => $content,
                'output_format' => 'text',
                'output_info'   => 'compiled_code',
            ) +
            $this->parameters +
            array(
                'compilation_level' => 'SIMPLE_OPTIMIZATIONS'
            ),
            null,
            '&'
        );

        $opts = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context = stream_context_create($opts);

        $result = file_get_contents('https://closure-compiler.appspot.com/compile', false, $context);

        if (false !== $result && trim($result) !== '') {
            return $result;
        }

        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function filterFile($content, $file, \SplFileInfo $fileInfo, $bundleUrl, $bundlePath)
    {
        return $content;
    }
}
