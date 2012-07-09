<?php

namespace ZfcFacebook\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use ZfcFacebook\Module as Module;
use ZfcFacebook\Facebook;
use ZfcFacebook\Exception;
use ZfcFacebook;

abstract class AbstractIframeRestfulController extends AbstractRestfulController
{

    /**
     * @var Facebook;
     */
    protected $facebook;

    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        $events->attach('dispatch', array($this, 'preDispatch'), 100);
    }

    public function preDispatch (MvcEvent $e)
    {
        $this->facebook = $this->getServiceLocator()->get('facebook');
    }

}
