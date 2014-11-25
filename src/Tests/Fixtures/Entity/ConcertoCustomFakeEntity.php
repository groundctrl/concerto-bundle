<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity;

use Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareInterface;
use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
use Doctrine\Common\PropertyChangedListener;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\ORM\Repository\ConcertoFakeEntityCustomRepository")
 */
class ConcertoCustomFakeEntity extends ConcertoTestUnawareEntity implements SoloistAwareInterface
{

    protected $soloist;

    public function getSoloist()
    {
        return $this->soloist;
    }

    public function setSoloist(Soloist $soloist)
    {
        if($this->soloist === $soloist){
            return;
        }else{
            $this->soloist = $soloist;
        }
    }

    public function addPropertyChangedListener(PropertyChangedListener $listener)
    {
    }
}
