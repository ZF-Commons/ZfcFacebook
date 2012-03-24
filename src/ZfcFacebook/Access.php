<?php
namespace ZfcFacebook;

use ZfcFacebook\Exception\AccessException,
    Zend\Http\Request,
    Zend\Http\Client,
    ZfcFacebook\Auth;

abstract class Access
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
     * Gets data from a given URI of the Facebook Graph API
     * @param string $uri URI to query
     * @param array $params An option array of parameters to pass
     * @return stdClass
     */
    public function getFromGraph($uri, array $params=null)
    {
        $uri = self::FACEBOOK_GRAPH_URI.$uri;
        if(!is_array($params))
        {
            $params = array(
                'access_token' => $this->auth->getToken()
            );
        }
        else
        {
            array_merge($params, array(
                'access_token' => $this->auth->getToken()
            ));
        }
        $this->http->setUri($uri);
        $this->http->setParameterGet($params);
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
        if(isset($data->data))
        {
            return $data->data;
        }
        return $data;
    }

    /**
     * Grabs logged in user's Facebook Id
     * @return string User's Facebook Id
     */
    public function getFacebookId()
    {
        if(method_exists($this->auth, 'getFacebookID'))
        {
            $fbid = $this->auth->getFacebookID();
        }
        else
        {
            $user = $this->getCurrentUserInfo();
            $fbid = $user['id'];
        }
        return $fbid;
    }

    /**
     * Returns basic user information from the Facebook Graph API
     * @param string $fbid Facebook ID of user
     * @return \stdClass
     */
    public function getCurrentUserInfo()
    {
        $uri = self::FACEBOOK_GRAPH_URI.'me';
        return $this->getFromGraph($uri);
    }

    public function runFqlQuery($fql)
    {
        $uri = self::FACEBOOK_GRAPH_URI.'/fql';
        $params = array('q' => $fql);
        $result = $this->getFromGraph($uri,$params);
        if(count($result) === 1)
        {
            return $result->data[0];
        }
        return $result->data;
    }

}