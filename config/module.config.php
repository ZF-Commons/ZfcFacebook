<?php
return array(
    'ZfcFacebook' => array(
        'appid' => '113017628724890',
        'appsecret' => 'b07b3178d54ac9117bd8067ca0a82696',
        'iframeapp' => true,
    ),
    'di' => array(
        'instance' => array(
            'alias' => array(
                'facebook' => 'ZfcFacebook\Controller\FacebookController',
            ),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'ZfcFacebook' => __DIR__ . '/../view',
                    ),
                ),
            ),
        ),
    ),
);
