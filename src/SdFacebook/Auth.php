<?php
namespace SdFacebook;

interface Auth
{              
    public function getToken();

    public function getFacebookId();
}