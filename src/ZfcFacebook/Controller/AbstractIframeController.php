<?php

namespace ZfcFacebook\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
    Zend\Mvc\MvcEvent,
    ZfcFacebook\Module as Module,
    ZfcFacebook\Facebook,
    ZfcFacebook\Exception,
    ZfcFacebook;

abstract class IframeController extends ActionController
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
