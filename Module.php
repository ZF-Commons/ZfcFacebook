<?php

namespace ZfcFacebook;

use Zend\Module\Manager,
    Zend\EventManager\Event,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider,
    Zend\Di\Di,
    ZfcFacebook\Facebook;

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
        $request = $e->getParam('request');
        $di = new Di();
        $di->get('ZfcFacebook\Facebook', array(
            'fbSecret' => self::getOption('appsecret'),
            'fbClientId' => self::getOption('appid'),
            'request' => $request
        ));
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
