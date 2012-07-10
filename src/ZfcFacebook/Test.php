<?php
namespace ZfcFacebook;
use ZfcFacebook\Graph;

/**
 * @property string $id The album ID
 * @property Graph\Album\Photo[] $from The profile that created this album
 */
class Test
{

    /**
     * @param $name
     * @return Graph\Album\Photo[]
     */
    public function __get($name)
    {
        if($name === 'from')
        {
            return array(new Graph\Album\Photo());
        }
    }
}
