<?php

/*
 * This file is part of BundleFu.
 *
 * (c) 2011 Jan Sorgalla <jan.sorgalla@dotsunited.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DotsUnited\BundleFu\Filter;

/**
 * DotsUnited\BundleFu\Filter\CssUrlRewriteFilter
 *
 * @author  Claudio Beatrice <claudi0.beatric3@gmail.com>
 * @version @package_version@
 */
class CssOptimizeFilter implements FilterInterface
{
    protected $file;
    protected $bundleUrl;
    protected $options = array(
        'optimizeColors' => true,
    );
    protected $regexps = array(
        // strip comments away, borrowed by Samstyle Framework
        "`^([\t\s]+)`ism" => '',
        "`^\/\*(.+?)\*\/`ism" => '',
        "`([\n\A;]+)\/\*(.+?)\*\/`ism" => '$1',
        "`([\n\A;\s]+)//(.+?)[\n\r]`ism" => '$1',
        "`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism" => "\n",

        // strip carriage returns and line feeds away
        '/(\r\n|\r|\n)/' => '',

        // transform remaining whitespaces in spaces
        '/\s+/' => ' ',

        // remove spaces before symbols where safe
        '/\s+([{};>+\(\[,])/' => '\1',

        // remove spaces after symbols where safe
        '/([!{}:;>+\(\[,])\s+/' => '\1',

        // strip units from zero values
        '/([\s:])(0)(px|em|%|in|cm|mm|pc|pt|ex)/' => '$1$2',

        // strip multiple zeroes down to one
        '/:0([\ \t]+0){1,3};/' => ':0;',

        // fix the previous regexp for a corner case
        '/background-position\:0\;/' => 'background-position:0 0;',

        // remove "0." integer parts from floating numbers where safe
        '/(:|\s)0+\.(\d+)/' => '$1.$2',

        // remove ".0" decimals from floating numbers
        '/(\d+)\.0+(p(?:[xct])|(?:[cem])m|%|in|ex)\b/' => '$1$2',

        // remove redundant top-right-bottom-left values
        '/:\s*(0|(?:(?:\d*\.?\d+(?:p(?:[xct])|(?:[cem])m|%|in|ex))))(\s+\1){1,3}[\s]*;/' => ':$1;',

        // remove semicolon before a closing bracket
        '/\;\s+\}/' => '}',

        // remove multiple semicolons
        '/;;+/' => ';',

        // remove empty rules
        '/^([\ \t]+)?[.#:A-Za-z][\s\w\d]*{[\s;]*}([\ \t]+)?$/' => '',

        // trim the file
        '/^[\t\ ]+|[\t\ ]+$/' => '',
    );

    /**
     * Initialize the filter
     *
     * @params array $options
     *   It can contain the following keys:
     *     - optimizeColors: a boolean to switch on/off color optimization
     *     - regexps: an array to add custom regexps to the filter.
     *                each entry is an array and should have the regex as key
     *                and the replacement value as value
     */
    public function __construct($options = array())
    {
        $this->options = array_merge($this->options, $options);

        if (isset($this->options['regexps'])) {
            $this->regexps = array_merge($this->regexps, $this->options['regexps']);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function filter($content)
    {
        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function filterFile($content, $file, \SplFileInfo $fileInfo, $bundleUrl, $bundlePath)
    {
        $this->file = $file;
        $this->bundleUrl = $bundleUrl;

        if (empty($content)) {
            throw new \Exception('Empty file content');
        }

        if ($this->getOption('optimizeColors', true)) {
            $this->optimizeColors();
        }

        return preg_replace(array_keys($this->regexps), $this->regexps, $content);
    }

    protected function getOption($name, $default = null) {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    protected function optimizeColors($content = '') {
        $colors = array(
            // Color names shorter than hex notation. Except for red.
            'F0FFFF' => 'azure',
            'F5F5DC' => 'beige',
            'FFE4C4' => 'bisque',
            'A52A2A' => 'brown',
            'FF7F50' => 'coral',
            'FFD700' => 'gold',
            '808080' => 'grey',
            '008000' => 'green',
            '4B0082' => 'indigo',
            'FFFFF0' => 'ivory',
            'F0E68C' => 'khaki',
            'FAF0E6' => 'linen',
            '800000' => 'maroon',
            '000080' => 'navy',
            '808000' => 'olive',
            'FFA500' => 'orange',
            'DA70D6' => 'orchid',
            'CD853F' => 'peru',
            'FFC0CB' => 'pink',
            'DDA0DD' => 'plum',
            '800080' => 'purple',
            'FA8072' => 'salmon',
            'A0522D' => 'sienna',
            'C0C0C0' => 'silver',
            'FFFAFA' => 'snow',
            'D2B48C' => 'tan',
            '008080' => 'teal',
            'FF6347' => 'tomato',
            'EE82EE' => 'violet',
            'F5DEB3' => 'wheat',
            // Hex notation shorter than named value
            'black'          => '#000',
            'fuchsia'        => '#f0f',
            'lightSlategray' => '#789',
            'lightSlategrey' => '#789',
            'magenta'        => '#f0f',
            'white'          => '#fff',
            'yellow'         => '#ff0',
        );

        // transform rgb color values to hex
        $content = preg_replace_callback(
            '/(?<=:)[\s]*rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)\s*;/',
            create_function(
                '$rgb',
                'return "#" .
                str_pad(dechex($rgb[1]), 2, STR_PAD_LEFT) .
                str_pad(dechex($rgb[2]), 2, STR_PAD_LEFT) .
                str_pad(dechex($rgb[3]), 2, STR_PAD_LEFT);'
            ),
            $content
        );

        // compress a hex value to three digits format when possible
        $content = preg_replace(
            '/(?<=:)[\s]*#([0-9a-fA-F])\1([0-9a-fA-F])\2([0-9a-fA-F])\3/',
            '#\1\2\3',
            $content
        );

        // transforming hex values in color codes and vice-versa when convenient
        foreach ($colors as $key => $color) {
            // check values only on the right side of the colon
            $content = preg_replace(
                '/(?<=:)[\s]*$key/',
                $color,
                $content
            );
        }

        return $content;
    }
}
