<?php
namespace ZfcFacebook\Graph\Tag;

use ZfcFacebook\Graph\Entity;

/**
 * @property string $id Id of person or company tagged
 * @property string $name Name of company or person tagged
 * @property float $x The center of the tag's horizontal position
 * @property float $y The center of the tag's vertical position
 * @property string $created_time The time the tag was initially created (ISO-8601 date-time)
 */
class Tag extends Entity
{
    protected $id;
    protected $name;
    protected $x;
    protected $y;
    protected $created_time;
    protected $type;
}
