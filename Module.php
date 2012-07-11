<?php

namespace ZfcFacebook;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getViewHelperConfiguration()
    {
        return array(
            'factories' => array(
                'ZfcFacebookSignedRequest' => function ($sm)
                {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\ZfcFacebookSignedRequest();
                    $viewHelper->setFacebook($locator->get('facebook'));
                    return $viewHelper;
                },
                'ZfcFacebookAuthUrl' => function ($sm)
                {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new View\Helper\ZfcFacebookAuthUrl();
                    $viewHelper->setFacebook($locator->get('facebook'));
                    return $viewHelper;
                },
            ),
        );
    }

    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'facebook' => function($sm)
                {
                    $config = $sm->get('config');
                    $facebook = new Facebook($config['ZfcFacebook'], $sm->get('request'));
                    return $facebook;
                },
                'facebookId' => function($sm)
                {
                    $facebook = $sm->get('facebook');
                    return $facebook->getFacebookId();
                }
            ),
        );
    }

}