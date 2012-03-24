<?php
namespace ZfcFacebook\Graph\Album;

use ZfcBase\Entity\Entity,
    ZfcFacebook\Graph\Profile\Profile,
    ZfcFacebook\Graph\Photo\PhotoCollection;


/**
 * @property string $id The album ID
 * @property Profile $from The profile that created this album
 * @property string $name The title of the album
 * @property string $description The description of the album
 * @property string $location The location of the album
 * @property string $link A link to this album on Facebook
 * @property string $cover_photo The album cover photo ID
 * @property string $can_upload Can user upload to this album?
 * @property string $privacy The privacy settings for the album
 * @property int $count The number of photos in this album
 * @property string $type The type of the album: `profile`, `mobile`, `wall`, `normal` or `album`
 * @property string $created_time The time the photo album was initially created (ISO-8601 date-time)
 * @property array $likes The information about user likes
 * @property PhotoCollection $photos The Photos in this album
 * @property array $comments
 * @property \ZfcFacebook\Access $api
 */
class Album extends Entity
{
    protected $id;
    protected $from;
    protected $name;
    protected $description;
    protected $location;
    protected $link;
    protected $cover_photo;
    protected $can_upload=false;
    protected $privacy;
    protected $count;
    protected $type;
    protected $created_time;
    protected $updated_time;
    protected $likes;
    protected $comments;
    protected $photos;
    protected $api;

    /**
     * Gets a specific album from the graph
     * @param $id string
     * @return Album
     */
    public function getAlbum($id)
    {
        $this->setFromStdClass($this->api->getFromGraph("/{$id}"));
        return $this;
    }

    /**
     * Lazy loads photos for this album
     * @return PhotoCollection
     */
    protected function getPhotos()
    {
        if($this->photos instanceof PhotoCollection)
        {
            return $this->photos;
        }
        $photos = $this->api->getFromGraph("/{$this->id}/photos");
        $this->photos = new PhotoCollection($photos);
        return $this->photos;
    }

}