<?php
namespace ZfcFacebook;

interface Auth
{              
    public function getToken();

    public function getFacebookId();
}