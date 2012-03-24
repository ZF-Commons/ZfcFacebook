<?php

namespace ZfcFacebook\Graph\Profile;

use ZfcFacebook\Graph\Entity;

/**
 * @property string $id The unique Id of this profile
 * @property string $name The name of the user or page
 * @property string $category The category this page is in (only if page)
 */
class Profile extends Entity
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $category;
}
