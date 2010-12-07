<?php
/**
 * Du\BundleFu
 *
 * LICENSE
 *
 * This source file is subject to the BSD license that is available
 * through the world-wide-web at this URL:
 * https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Integration
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 */

/**
 * Twig_Extension_BundleFu
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Integration
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 */
class Twig_Extension_BundleFu extends Twig_Extension
{
    /**
     * @var \Du\BundleFu\BundleFu
     */
    protected $_bundleFu;

    /**
     * Constructor
     */
    public function __construct()
    {
        spl_autoload_register(function($className) {
            if (strpos($className, 'Du\\BundleFu\\') === 0) {
                require str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
            }
        });
    }

    /**
     * Set the BundleFu instance.
     *
     * @param \Du\BundleFu\BundleFu $bundleFu
     * @return Zend_View_Helper_BundleFu
     */
    public function setBundleFu(\Du\BundleFu\BundleFu $bundleFu)
    {
        $this->_bundleFu = $bundleFu;
        return $this;
    }

    /**
     * Get the BundleFu instance.
     *
     * @return \Du\BundleFu\BundleFu
     */
    public function getBundleFu()
    {
        if (null === $this->_bundleFu) {
            $this->_bundleFu = new \Du\BundleFu\BundleFu();
        }

        return $this->_bundleFu;
    }

    /**
     * Call a method on the BundleFu instance.
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        $return = call_user_func_array(array($this->getBundleFu(), $method), $params);

        switch ($method) {
            case 'start':
            case 'end':
            case substr($method, 0, 3) == 'set':
                return $this;
            default:
                return $return;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bundlefu';
    }

    /**
     * @return array
     */
    public function getTokenParsers()
    {
        return array(new Twig_TokenParser_BundleFu());
    }
}

/**
 * Twig_TokenParser_BundleFu
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Integration
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 */
class Twig_TokenParser_BundleFu extends Twig_TokenParser
{
    /**
     * @param \Twig_Token $token
     * @return Node
     */
    public function parse(Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        if ($stream->test(Twig_Token::BLOCK_END_TYPE)) {
            $method    = 'render';
            $arguments = null;
        } else {
            $method    = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
            $arguments = $this->parser->getExpressionParser()->parseArguments();
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new Twig_Node_BundleFu($method, $arguments, $lineno, $this->getTag());
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return 'bundlefu';
    }
}

/**
 * Twig_Node_BundleFu
 *
 * @category   Du
 * @package    Du_BundleFu
 * @subpackage Integration
 * @author     Jan Sorgalla
 * @copyright  Copyright (C) 2010 - Present, Jan Sorgalla
 * @license    https://github.com/dotsunited/du-bundlefu/blob/master/LICENSE New BSD License
 */
class Twig_Node_BundleFu extends Twig_Node
{
    /**
     * @param string $method
     * @param Twig_NodeInterface $arguments
     * @param integer $lineno
     * @param string $tag
     */
    public function __construct($method, Twig_NodeInterface $arguments = null, $lineno, $tag = null)
    {
        parent::__construct(array('arguments' => $arguments), array('method' => $method), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile($compiler)
    {
        $compiler->addDebugInfo($this);

        $compiler->write('if (!isset($__bundleFu)) { $__bundleFu = $this->env->getExtension(\'bundlefu\'); }' . "\n");

        switch (strtolower($this['method'])) {
            case 'render':
            case 'rendercss':
            case 'renderjs':
                $echo = 'echo ';
                break;
            default:
                $echo = '';
        }

        $compiler->write($echo . '$__bundleFu->' . $this['method'] . '(');

        if (null !== $this->arguments) {
            foreach ($this->arguments as $idx => $argument) {
                if ($idx) {
                    $compiler->raw(', ');
                }
                $compiler->subcompile($argument);
            }
        }

        $compiler->raw(");\n");
    }
}
