<?php
namespace ZfcFacebook\Graph\Album;

use ZfcBase\Collection\Collection;

/**
 * @method \ZfcFacebook\Graph\Album\Album current()
 * @method \ZfcFacebook\Graph\Album\Album[] toArray()
 * @method \ZfcFacebook\Graph\Album\Album offsetGet()
 * @method \ZfcFacebook\Graph\Album\Album buildEntity()
 */
class AlbumCollection extends Collection
{
    protected $entity = '\ZfcFacebook\Graph\Album\Album';
}