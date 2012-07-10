<?php

namespace ZfcFacebook\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ZfcFacebookSignedRequest extends AbstractHelper
{

    /**
     * @var \ZfcFacebook\Facebook
     */
    protected $facebook;

    /**
     * @return string
     */
    public function __invoke()
    {
        return $this->facebook->getAuth()->getSignedRequest();
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
