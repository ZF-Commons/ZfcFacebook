<?php

namespace ZfcFacebook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use ZfcFacebook\Module as Module;
use ZfcFacebook\Facebook;
use ZfcFacebook\Exception;
use ZfcFacebook;

abstract class AbstractIframeController extends AbstractActionController
{

    /**
     * @var \ZfcFacebook\Facebook
     */
    protected $facebook;

    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        /**
         * @todo Think of how we can automatically redirect to allow page if user hasn't installed
         */
        $this->events->attach('dispatch', array($this, 'preDispatch'), 100);
    }

    public function preDispatch (MvcEvent $e)
    {

        $facebook = $this->getFacebook();
        try
        {
            $facebook->getFacebookId();
        }
        catch (Exception\AuthException $err)
        {
            if($err->getCode() !== ZfcFacebook\Auth::ERROR_NOTINSTALLED)
            {
                throw new Exception\AuthException($err->getMessage(), $err->getCode());
            }
//            $e->getRouteMatch()->setParam('controller', Module::getOption('notinstalledcontroller'));
            $e->getRouteMatch()->setParam('action', $facebook->options['notinstalledaction']);
        }
    }

    /**
     * @return \ZfcFacebook\Facebook
     */
    public function getFacebook()
    {
        if(!($this->facebook instanceof Facebook)) {
            $this->facebook = $this->getServiceLocator()->get('facebook');
        }
        return $this->facebook;
    }


}
