<?php
namespace ZfcFacebook\Graph\Tag;

use ZfcBase\Entity\Entity;

/**
 * @property int $width Width of image
 * @property int $height Height of image
 * @property string $source Location of image (Valid URL)
 */
class Image extends Entity
{
    protected $width;
    protected $height;
    protected $source;
}
