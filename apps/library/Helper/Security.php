<?php
/**
 * Created by PhpStorm.
 * User: xiaoqizhi
 * Date: 15/7/27
 * Time: 下午4:04
 * Ref :  https://github.com/phalcon/cphalcon/issues/2596
 */
namespace Library\Helper;

/**
 * Security class adds checks for missing token and config specified GET requests.
 */
class Security extends \Phalcon\Security
{

    function __construct() {
    }

    /**
     * Force CSRF tokens on these Controllers/actions. Most often GETs.
     *
     * @var array An array of Controllers/actions i.e. array('controller'=>array('action1','action2'))
     */
    private $_forced;

    /**
     * Exempt from CSRF tokens on these Controllers/actions. Most often POSTs.
     *
     * @var array An array of Controllers/actions i.e. array('controller'=>array('action1','action2'))
     */
    private $_exempt;

    /**
     * Set CSRF token forced Controllers/actions.
     *
     * @param string $routes An array of Controllers/actions i.e. array('controller'=>array('action1','action2'))
     */
    public function setForced($routes)
    {
        if (is_array($routes)) $this->_forced = $routes;
    }

    /**
     * Set CSRF token exempt Controllers/actions.
     *
     * @param string $routes An array of Controllers/actions i.e. array('controller'=>array('action1','action2'))
     */
    public function setExempt($routes)
    {
        if (is_array($routes)) $this->_exempt = $routes;
    }

    /**
     * Is the controller/action forced.
     *
     * @return bool
     */
    public function isForced()
    {

        $controller = $this->getDi()->getRouter()->getControllerName();
        $action = $this->getDi()->getRouter()->getActionName();

        if (isset($this->_forced[$controller])) {
            if (in_array($action, $this->_forced[$controller])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Is the controller/action exempt.
     *
     * @return bool
     */
    public function isExempt()
    {

        $controller = $this->getDi()->getRouter()->getControllerName();
        $action = $this->getDi()->getRouter()->getActionName();

        if (isset($this->_exempt[$controller])) {
            if (in_array($action, $this->_exempt[$controller])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retrieve the session token key
     *
     * @return string
     */
    public function getSessionTokenKey()
    {
        return $this->getDi()->getSession()->get('csrf_token');
    }

    public function getToken()
    {
        $token = 't_' . (time() + 7200);
        $this->getDI()->get('session')->set('csrf-token', $token);
        return $token ;
    }


    /**
     * Check the session token key exists
     *
     * @return bool
     */
    public function hasSessionTokenKey()
    {
        return $this->getDi()->getSession()->has('csrf_token');
    }

    /**
     * Check for CSRF on POSTs and 'protected' GETs, including missing tokens from session expiry.
     *
     * @returns bool
     */
    public function checkAll()
    {

        $controller = $this->getDi()->getRouter()->getControllerName();
        $action = $this->getDi()->getRouter()->getActionName();

        // Check exempt list
        if ($this->isExempt()) return true;

        // POST request
        if ($this->getDi()->getRequest()->isPost()) {
            // Always forced on POSTs unless exempted above
            return $this->checkToken();

            // GET Request
            // Check forced list
        } else if ($this->isForced()) {
            // Retrieve the tokenKey from session if it exists
            $tokenKey = $this->getSessionTokenKey();
            return $this->checkToken($tokenKey, $this->getDi()->getRequest()->getQuery($tokenKey, 'string'));
        }

        // Always exempt on GETs unless forced.
        return true;
    }

    /**
     * Overload of token check to include failure on missing session token.
     *
     * @param string $tokenKey
     * @param string $token
     *
     * @returns bool
     */
    public function checkToken($tokenKey = NULL, $token = NULL , $ex=null)
    {


//        echo 'hellowww:'.$uri;
        // Token must be set on forced actions, expired session will be without it, see issue #15 #22 and https://github.com/phalcon/cphalcon/issues/2596
        if (!$this->hasSessionTokenKey()) return false; // No checked request will be allowed without a tokenKey in session

        return parent::checkToken($tokenKey, $token);
    }

}