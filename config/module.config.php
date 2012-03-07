<?php
return array(
    'sdfacebook' => array(
        'appid' => '',
        'appsecret' => '',
        'iframeapp' => true,
    ),
    'di' => array(
        'instance' => array(
            'alias' => array(
                'facebook' => 'SdFacebook\Controller\FacebookController',
            ),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'SdFacebook' => __DIR__ . '/../view',
                    ),
                ),
            ),
        ),
    ),
);
