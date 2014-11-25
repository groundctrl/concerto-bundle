<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity;


use Ctrl\Bundle\ConcertoBundle\Traits\SoloistAwareTrait;
use Doctrine\Common\NotifyPropertyChanged;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ConcertoTestTraitEntity
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("NOTIFY")
 */
class ConcertoTestTraitEntity implements NotifyPropertyChanged
{
    use SoloistAwareTrait;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    public $id;



    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->id = 1;
    }
}