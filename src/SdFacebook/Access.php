<?php
namespace SdFacebook;

use SdFacebook\Exception\AccessException,
    Zend\Http\Request,
    Zend\Http\Client,
    SdFacebook\Auth;

class Access
{
    /** @var Auth **/
    protected $auth;
    /** @var Zend\Http **/
    protected $http;
    
    const FACEBOOK_GRAPH_URI = 'https://graph.facebook.com/';
    /**
     * Constructor
     * @param $auth Facebook Auth Object
     */
    public function __construct(Auth $auth, Client $http)
    {
        $this->setAuth($auth);
        $this->http = $http;
    }
    
    /**
     * Sets the Facebook Authentication Object to use
     * @param type $auth Facebook Auth Object
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }
    
    /**
     * Returns basic user information from the Facebook Graph API
     * @param string $fbid Facebook ID of user
     * @return \stdClass
     */
    public function getUserInfo($fbid=null)
    {
        if(empty($fbid))
        {
            if(method_exists($this->auth, 'getFacebookID'))
            {
                $fbid = $this->auth->getFacebookID();
            }
            else
            {
                $fbid = $this->getFacebookID();
            }
            
        }
        $uri = self::FACEBOOK_GRAPH_URI.$fbid;        
        return $this->getFromGraph($uri);
    }
    
    /**
     * Gets data from a given URI of the Facebook Graph API
     * @param string $uri URI to query
     * @return stdClass
     */
    protected function getFromGraph($uri)
    {
        $this->http->setUri($uri);
        $this->http->setParameterGet(array(
            'access_token' => $this->auth->getToken()
        ));
        return $this->parseFromGraph($this->http->send()->getBody());
    }
    
    /**
     * Parses data returned from the Facebook Graph Api
     * @throws \Spabby\Facebook\Exception\AccessException
     * @param string $body The JSON encoded body returned from the http request
     * @return stdClass
     */
    protected function parseFromGraph($body)
    {
        $data = \Zend\Json\Decoder::decode($body);
        if(!\is_object($data))
        {
            throw new AccessException("Invalid Facebook Response");
        }
        if(\property_exists($data, 'error'))
        {
            throw new AccessException("{$data->error->type}: 
            {$data->error->message}");
        }
        
        return $data;
    }
    
    public function getFacebookID()
    {
        $uri = self::FACEBOOK_GRAPH_URI.'/me';
        pr($this->getFromGraph($uri));
    }
}