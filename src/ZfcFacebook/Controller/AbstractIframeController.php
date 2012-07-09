<?php

namespace ZfcFacebook\Controller;

use Zend\Mvc\Controller\AbstractActionController as ActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use ZfcFacebook\Module as Module;
use ZfcFacebook\Facebook;
use ZfcFacebook\Exception;
use ZfcFacebook;

abstract class AbstractIframeController extends ActionController
{

    /**
     * @var Facebook;
     */
    protected $facebook;

    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->events();
        $events->attach('dispatch', array($this, 'preDispatch'), 100);
    }

    public function preDispatch (MvcEvent $e)
    {
        $this->facebook = new Facebook(
            Module::getOption('appsecret'),
            Module::getOption('appid'),
            $e->getRequest()
        );
        try
        {
            $this->facebook->getFacebookId();
        }
        catch (Exception\AuthException $err)
        {
            if($err->getCode() !== ZfcFacebook\Auth::ERROR_NOTINSTALLED)
            {
                throw new Exception\AuthException($err->getMessage(), $err->getCode());
            }
//            $e->getRouteMatch()->setParam('controller', Module::getOption('notinstalledcontroller'));
            $e->getRouteMatch()->setParam('action', Module::getOption('notinstalledaction'));
        }
    }

}
