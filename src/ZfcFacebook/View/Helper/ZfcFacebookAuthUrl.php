<?php

namespace ZfcFacebook\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ZfcFacebookAuthUrl extends AbstractHelper
{

    /**
     * @var \ZfcFacebook\Facebook
     */
    protected $facebook;

    /**
     * @param $redirectUrl
     * @return string
     */
    public function __invoke($redirectUrl)
    {
        return $this->facebook->getAuthUrl($redirectUrl);
    }

    /**
     * @param \ZfcFacebook\Facebook $facebook
     * @return ZfcFacebookSignedRequest
     */
    public function setFacebook(\ZfcFacebook\Facebook $facebook)
    {
        $this->facebook = $facebook;
        return $this;
    }
}
