<?php
namespace ZfcFacebook\Auth;

use Zend\Http\PhpEnvironment\Request;
use ZfcFacebook\Auth as Auth;
use ZfcFacebook\Exception;

class Iframe implements Auth
{
    /**
     * Facebook secret for application
     * @var string
     */
    protected $fbSecret;
    /**
     * @var Request;
     */
    protected $request;
    /**
     * Decoded Facebook sigs
     * @var array
     */
    protected $decodedSigs;
    /**
     * @var array
     */
    protected $configuration;

    /**
     * @param $fbSecret
     * @param Request $request
     * @param array $configuration
     */
    public function __construct($fbSecret, Request $request, array $configuration)
    {
        $this->fbSecret = $fbSecret;
        $this->request = $request;
        $this->configuration = $configuration;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        $decodedSigs = $this->getDecodedSigs();
        return (string)$decodedSigs['oauth_token'];
    }

    /**
     * Returns auth'd user's Facebook ID
     * @return string
     */
    public function getFacebookId()
    {
        $decodedSigs = $this->getDecodedSigs();
        return (string)$decodedSigs['user_id'];
    }

    /**
     * Returns the decoded facebook sigs
     * @throws \ZfcFacebook\Exception\AuthException
     * @return array
     */
    public function getDecodedSigs()
    {
        if (!is_array($this->decodedSigs)) {
            $this->decodedSigs = $this->parseSignedRequest();
            if(!$this->decodedSigs) {
                return false;
            }
        }
        if (!is_array($this->decodedSigs)) {
            throw new Exception\AuthException("Invalid decoded sigs");
        }
        $decodedSigs = $this->decodedSigs;
        if (!array_key_exists('expires', $decodedSigs)
            || !array_key_exists('oauth_token', $decodedSigs)
        ) {
            throw new Exception\AuthException("Token details do not exist");
        }
        if ($decodedSigs['expires'] < time()) {
            throw new Exception\AuthException("Token has expired");

            if (!array_key_exists('user', $decodedSigs)) {
                throw new  Exception\AuthException("Token details do not exist");
            }
            throw new  Exception\AuthException("User has not installed application", self::ERROR_NOTINSTALLED);
        }
        if ($decodedSigs['expires'] < time()) {
            throw new  Exception\AuthException("Token has expired");
        }
        return $this->decodedSigs;
    }

    /**
     * Decodes signed request
     * @throws Exception\AuthException
     * @return array
     */
    protected function parseSignedRequest()
    {
        // Check vars
        if (!is_string($this->fbSecret) || empty($this->fbSecret)) {
            throw new Exception\AuthException('Invalid Facebook Secret');
        }
        $signedRequest = $this->getSignedRequest();
        if(!$signedRequest) {
            return false;
        }
        list($encodedSig, $payload) = \explode('.', $signedRequest, 2);

        // Decode the data
        $decodedSig = $this->base64UrlDecode($encodedSig);
        $data = \json_decode($this->base64UrlDecode($payload), true);
        if (\strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            throw new Exception\AuthException('Invalid Signed Request');
        }

        // Check sig
        $expectedSig = \hash_hmac('sha256', $payload, $this->fbSecret, $raw = true);
        if ($decodedSig !== $expectedSig) {
            throw new Exception\AuthException('Invalid Signed Request');
        }

        return $data;

    }

    /**
     * @return mixed
     * @throws \ZfcFacebook\Exception\AuthException
     */
    public function getSignedRequest()
    {
        $signedRequest = ($this->request->getPost()->get('signed_request')
            ? $this->request->getPost()->get('signed_request')
            : $this->request->getQuery()->get('signed_request'));
        if (empty($signedRequest)) {
            if($this->configuration['allowoutsidefacebook']) {
                return false;
            }
            throw new Exception\AuthException('No signed_request has been posted, are you trying to access outside Facebook?');
        }
        return $signedRequest;
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
