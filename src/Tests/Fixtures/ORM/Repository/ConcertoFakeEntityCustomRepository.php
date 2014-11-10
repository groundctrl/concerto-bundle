<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\ORM\Repository;


use Doctrine\ORM\EntityRepository;

class ConcertoFakeEntityCustomRepository extends EntityRepository
{
    protected $soloistField;

    public function getSoloistField()
    {
        return $this->soloistField;
    }

    public function setSoloistField($sf)
    {
        if($sf == $this->soloistField){
            return;
        } else {
            $this->soloistField = $sf;
        }
    }

    public function repoSoloMethod($str)
    {

    }
} 