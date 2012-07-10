<?php
namespace ZfcFacebook\Graph\Photo;

use ZfcBase\Entity\Entity,
    ZfcFacebook\Graph\Image\ImageCollection;

/**
 * @property string $id The photo ID
 * @property \ZfcFacebook\Graph\Profile $from The profile (user or page) that posted this photo
 * @property \ZfcFacebook\Graph\Tag\TagCollection $tags The tagged users and their positions in this photo
 * @property string $name The user provided caption given to this photo
 * @property string $icon The icon that Facebook displays when photos are published to the Feed (Valid URL)
 * @property string $picture The thumbnail-sized source of the photo (Valid URL)
 * @property string $source The source image of the photo (Valid URL)
 * @property int $height The height of the photo in pixels
 * @property int $width The width of the photo in pixels
 * @property ImageCollection $images The 4 different stored representations of the photo
 * @property string $link A link to the photo on Facebook
 * @property /stdClass $location Location associated with a Photo
 * @property string $created_time The time the photo was initially published (ISO-8601 date-time)
 * @property string $updated_time The last time the photo or its caption was updated
 * @property int $position The position of this photo in the album
 *
 */
class Photo extends Entity
{
    protected $id;
    protected $from;
    protected $tags;
    protected $name;
    protected $icon;
    protected $picture;
    protected $source;
    protected $height;
    protected $width;
    protected $images;
    protected $link;
    protected $place;
    protected $created_time;
    protected $updated_time;
    protected $position;

    protected function setImages(array $images)
    {
        $this->images = new ImageCollection($images);
        return $this;
    }
}