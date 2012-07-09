<?php

namespace ZfcFacebook\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ZfcFacebookSignedRequest extends AbstractHelper
{

    /**
     * @var \ZfcFacebook\Facebook
     */
    protected $facebook;

    public function __invoke()
    {
        return $this->facebook->getAuth()->getSignedRequest();
    }

    public function setFacebook(\ZfcFacebook\Facebook $facebook)
    {
        $this->facebook = $facebook;
        return $this;
    }
}
