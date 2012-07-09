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
                'ZfcFacebookSignedRequest' => function ($sm)
                {
                    $viewHelper = new View\Helper\ZfcFacebookSignedRequest();
                    $viewHelper->setFacebook($sm->get('facebook'));
                    return $viewHelper;
                },
            ),
        );
    }

}