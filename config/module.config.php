<?php
return array(
    'sdfacebook' => array(
        'appid' => '113017628724890',
        'appsecret' => '4a4854fb336828b2cab239b0a9da4dfa',
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
