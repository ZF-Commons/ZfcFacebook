<?php
namespace ZfcFacebook;

use ZfcFacebook\Access,
    ZfcFacebook\Auth;

class Common extends Access
{
    /**
     * Grabs a list of user's friends from the Graph API
     * @param null $fbid Facebook Id of User
     * @return array List of friends names and fbids
     */
    public function getUserFriends($fbid=null)
    {
        if(empty($fbid))
        {
            $fbid = $this->getFacebookId();
        }
        $uri = parent::FACEBOOK_GRAPH_URI.$fbid.'/friends';
        $friends = $this->getFromGraph($uri);
        return (array) $friends->data;
    }
}