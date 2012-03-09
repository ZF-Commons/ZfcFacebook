<?php

namespace ZfcFacebook\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
    ZfcFacebook\Module,
    ZfcFacebook;

class FacebookController extends ActionController
{
    public function indexAction()
    {
        $request = $this->getRequest();
        $facebook = new \ZfcFacebook\Facebook(
            Module::getOption('appsecret'),
            Module::getOption('appid'),
            $request
        );

        return array(
            'facebookid' => $facebook->getFacebookId(),
            'details' => $facebook->api()->getCurrentUserInfo(),
            'friends' => $facebook->api()->getUserFriends(),
            'fqlresult' => $facebook->api()->runFqlQuery('SELECT name, fan_count FROM page WHERE page_id = 103731186331757'),
        );
    }
}
