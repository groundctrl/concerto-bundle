<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

/**
 * @ORM\Entity
 */
class ConcertoRegularFakeEntity
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    public function __construct()
    {
        $this->id = rand(0,999);
    }
    public function getId()
    {
        return $this->id;
    }
}