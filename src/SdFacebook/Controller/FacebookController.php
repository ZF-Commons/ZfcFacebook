<?php

namespace SdFacebook\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
    SdFacebook\Module,
    SdFacebook;

class FacebookController extends ActionController
{
    public function indexAction()
    {
        $request = $this->getRequest();
        $facebook = new \SdFacebook\Facebook(
            Module::getOption('appsecret'),
            Module::getOption('appid'),
            $request
        );

        return array(
            'facebookid' => $facebook->getFacebookId(),
            'details' => $facebook->api()->getUserInfo(),
        );
    }
}
