<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity;


use Ctrl\Bundle\ConcertoBundle\Traits\ConcertoMemberTrait;
use Doctrine\Common\NotifyPropertyChanged;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ConcertoTraitFakeEntity
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("NOTIFY")
 */
class ConcertoTraitFakeEntity implements NotifyPropertyChanged
{
    use ConcertoMemberTrait;

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