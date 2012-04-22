<?php
namespace ZfcFacebook\Auth;

use ZfcFacebook\Exception,
    ZfcFacebook\Auth as Auth,
    Zend\Http\PhpEnvironment\Request;

class Iframe implements Auth
{    
    /**
     * Facebook secret for application
     * @var string
     */
    protected $fbSecret; 
    /**
     * @var Zend\Http\PhpEnvironment\Request;
     */
    protected $request;
    /**
     * Decoded Facebook sigs
     * @var array
     */
    protected $decodedSigs;
    /**
     * Constructor
     * @param type string
     * @param type string
     */
    public function __construct($fbSecret, Request $request)
    {
        $this->fbSecret = $fbSecret;
        $this->request = $request;
    }
    
    /**
     * Gets the signed request
     * @return string
     */
    public function getSignedRequest()
    {
        if(!$this->request->isPost())
        {
            throw new Exception\AuthException('No parameters have been posted, are you trying to access outside Facebook?');
        }

        $signedRequest = $this->request->post()->get('signed_request');
        if(empty($signedRequest))
        {
            throw new Exception\AuthException('No signed_request has been posted, are you trying to access outside Facebook?');
        }
        return $signedRequest;
    }
    
    /**
     * Returns the decoded facebook sigs
     * @throws Spabby\Facebook\AuthException
     * @return array
     */
    public function getDecodedSigs()
    {
        if(!is_array($this->decodedSigs))
        {
            $this->decodedSigs = $this->parseSignedRequest();
        }
        if(!is_array($this->decodedSigs))
        {
            throw new Exception\AuthException("Invalid decoded sigs");
        }
        $decodedSigs = $this->decodedSigs;
        if(!array_key_exists('expires', $decodedSigs)
                || !array_key_exists('oauth_token', $decodedSigs)) 
        {
            throw new Exception\AuthException("Token details do not exist");
        }
        if($decodedSigs['expires'] < time())
        {
            throw new Exception\AuthException("Token has expired");
        }
        return $this->decodedSigs;
    }
    
    /**
     * Returns the Facebook oauth_token from the decoded sigs
     * @throws Spabby\Facebook\AuthException
     * @return string
     */
    public function getToken()
    {
        $decodedSigs = $this->getDecodedSigs();
        return (string) $decodedSigs['oauth_token'];
    }
    
    /**
     * Returns auth'd user's Facebook ID
     * @return string
     */
    public function getFacebookId()
    {
        $decodedSigs = $this->getDecodedSigs();
        return (string) $decodedSigs['user_id'];            
    }
    
    /**
     * Decodes signed request
     * @throws Exception\AuthException
     * @return array 
     */
    protected function parseSignedRequest()
    {
        // Check vars
        if (!is_string($this->fbSecret) || empty($this->fbSecret))
        {
            throw new Exception\AuthException('Invalid Facebook Secret');
        }
        list($encodedSig, $payload) = \explode('.', $this->getSignedRequest(), 2);

        // Decode the data
        $decodedSig = $this->base64UrlDecode($encodedSig);
        $data = \json_decode($this->base64UrlDecode($payload), true);
        if (\strtoupper($data['algorithm']) !== 'HMAC-SHA256')
        {
            throw new Exception\AuthException('Invalid Signed Request');
        }

        // Check sig
        $expectedSig = \hash_hmac('sha256', $payload, $this->fbSecret, $raw = true);
        if ($decodedSig !== $expectedSig)
        {
            throw new Exception\AuthException('Invalid Signed Request');
        }

        return $data;

    }

    /**
     * Base 64 Url Decode string
     * @param string $input
     * @return string
     */
    protected function base64UrlDecode($input)
    {
        return \base64_decode(\strtr($input, '-_', '+/'));
    }
}