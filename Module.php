<?php

namespace ZfcFacebook;

use Zend\Module\Manager,
    Zend\EventManager\Event,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{
    protected static $options;

    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'onBootstrap'));
    }

    public function onBootstrap(Event $e)
    {
        $config = $e->getParam('config');
        static::$options = $config['ZfcFacebook'];
        $app = $e->getParam('application');
        $app->events()->attach('dispatch', array($this, 'onDispatch'), -100);
    }

    public function onDispatch(Event $e)
    {
        // this is an iframe app, we need to error if it doesn't have signed_request
//        if(self::getOption('iframeapp'))
//        {
//            $request = $e->getParam('request');
//            $signedRequest = $request->post()->get('signed_request');
//            if(empty($signedRequest))
//            {
//                throw new Exception\AuthException('No signed_request has been posted, are you trying to access outside Facebook?');
//            }
//        }
    }

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

    /**
     * @TODO: Copy EDP's better way of handling module settings/options
     */
    public static function getOption($option)
    {
        if (!isset(static::$options[$option])) {
            return null;
        }
        return static::$options[$option];
    }
}
