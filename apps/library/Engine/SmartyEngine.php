<?php

namespace Library\Engine;

use Phalcon\DiInterface;
use Phalcon\Mvc\View\Engine;
use Phalcon\Mvc\View\EngineInterface;
use Phalcon\Mvc\ViewBaseInterface;


require_once(dirname(dirname(__FILE__)).'/Smarty/Smarty.class.php');

/**
 * Phalcon\Mvc\View\Engine\Smarty
 * Adapter to use Smarty library as templating engine
 */
class SmartyEngine extends Engine implements EngineInterface
{
    /**
     * @var \Smarty
     */
    protected $smarty;

    public function __construct(ViewBaseInterface $view, \Phalcon\DiInterface $di = null)
    {

        $this->smarty = new \Smarty();

        /*
        $base_dir = realpath(dirname(__FILE__).'/../../test/').'/';
        $this->smarty->template_dir = $base_dir . '';
        $this->smarty->compile_dir  = $base_dir . 'templates_c';
        $this->smarty->config_dir   = $base_dir . 'configs';
        $this->smarty->cache_dir    = $base_dir . 'cache';
        $this->smarty->caching      = false;
        $this->smarty->debugging    = true;
        $this->smarty->assign( 'result', 'Success!!!!' );
        */

        parent::__construct($view, $di);
    }
    /**
     * {@inheritdoc}
     *
     * @param string  $path
     * @param array   $params
     * @param boolean $mustClean
     */
    public function render($path, $params, $mustClean = null)
    {
        if (!isset($params['content'])) {
            $params['content'] = $this->_view->getContent();
        }

        foreach ($params as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->_view->setContent($this->smarty->fetch($path));
    }
    /**
     * Set Smarty's options
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $k => $v) {
            $this->smarty->$k = $v;
        }
    }
}