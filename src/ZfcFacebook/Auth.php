<?php
namespace ZfcFacebook;

interface Auth
{
    const ERROR_NOTINSTALLED = 9001;

    /**
     * Base URL for composing an authorisation URL
     */
    const BASE_AUTH_URL = 'https://graph.facebook.com/oauth/authorize';

    public function getToken();

    public function getFacebookId();
}