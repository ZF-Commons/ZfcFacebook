<?php
namespace ZfcFacebook;

use Zend\Http\Client,
    ZfcFacebook\Module,
    ZfcFacebook\Exception,
    ZfcFacebook\Auth,
    Zend\Http\PhpEnvironment\Request;

/**
 * Container class for Facebook integration, contains automatic authentication
 * @todo Add extended permissions requests
 * @todo Add update/delete methods for graph
 * @todo Add FQL handler https://developers.facebook.com/docs/reference/fql/
 * @todo Add proxy methods to Access https://developers.facebook.com/docs/reference/api/
 * @todo View helpers for the fb js class https://developers.facebook.com/docs/reference/javascript/
 * @todo View helpers for social plugins https://developers.facebook.com/docs/plugins/
 * @todo Meta tag generation https://developers.facebook.com/docs/reference/plugins/like/
 * @todo View helpers for the FB Dialog system https://developers.facebook.com/docs/reference/dialogs/
 * @todo Facebook Credits API integration (may be seperate module)
 */
class Facebook
{

    /**
     * @var Request;
     **/
    protected $request;
    /**
     * @var string
     **/
    protected $fbSecret;
    /**
     * @var string
     **/
    protected $fbClientId;
    /**
     * @var Spabby\Facebook\Auth
     **/
    protected $auth;
    /**
     * @var ZfcFacebook\Access;
     **/
    protected $api;

    /**
     * Constructor
     * @param string $fbSecret  Facebook API Secret token
     * @param int $fbClientId  Facebook Client ID
     * @param string $request  Request object (optional)
     * @param string $fbCode  Access code passed from Facebook (optional)
     */
    public function __construct(
        $fbSecret,
        $fbClientId,
        Request $request=null,
        $fbCode=null)
    {
        $this->fbSecret = $fbSecret;
        $this->fbClientId = $fbClientId;
        $this->request = $request;
        $this->fbCode = $fbCode;
    }

    /**
     * Returns valid Facebook Auth object (if authentication is successful)
     * @return Facebook\Auth
     * @throws Facebook\Exception\AuthException
     */
    public function getAuth()
    {
        if(Module::getOption('iframeapp'))
        {
            // If no auth set, we can use Iframe auth, and the sigs are set, do it!
            if($this->auth instanceof Auth === false
                    && $this->request instanceof Request)
            {
                $this->auth = new Auth\Iframe($this->fbSecret, $this->request);
            }
            else if ($this->auth instanceof Auth === false)
            {
                throw new Exception\AuthException('Request object not passed');
            }
        }

//        // If no auth set, and we can use Connect auth, do it!
//        if($this->auth instanceof Facebook\Auth === false
//                && $this->config['useconnectauth'])
//        {
//            $this->auth = new Facebook\Auth\Connect(
//                    $this->fbClientId,
//                    $this->fbSecret,
//                    new \Zend\Http\Client(),
//                    $this->fbCode);
//            $this->auth->getToken();
//        }
        if($this->auth instanceof Auth === false)
        {
            throw new Exception\AuthException('No valid Auth adapter found');
        }
        return $this->auth;
    }

    /**
     * Returns the Facebook Access Object
     * @return \ZfcFacebook\Access
     */
    public function api()
    {
        if(!is_a($this->api, 'Access'))
        {
            $this->api = new Common($this->getAuth(),
                    new Client());
        }
        return $this->api;
    }

    /**
     * Wrapper function to get the logged in user's Facebook Id
     * @return string
     */
    public function getFacebookId()
    {
        $auth = $this->getAuth();
        return $auth->getFacebookId();
    }

    public function getAuthUrl($redirectUrl = '/')
    {
        $authUrl = Auth::BASE_AUTH_URL
            .'?client_id='
            .Module::getOption('appid')
            .'&redirect_uri='
            .$redirectUrl
            .'&scope='
            .implode(',', Module::getOption('extendedperms')->toArray());
        return $authUrl;
    }

}