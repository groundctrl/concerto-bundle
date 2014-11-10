<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity;


use Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareInterface;
use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
use Ctrl\Bundle\ConcertoBundle\Traits\ConcertoMemberTrait;
use Doctrine\Common\PropertyChangedListener;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("NOTIFY")
 */
class ConcertoConcertoFakeEntity extends ConcertoRegularFakeEntity implements SoloistAwareInterface
{

    protected $soloist;

    public function getSoloist()
    {
        return $this->soloist;
    }

    public function setSoloist(Soloist $soloist = null)
    {
        if($this->soloist === $soloist){
            return;
        }else{
            $this->soloist = $soloist;
        }
    }

    public function addPropertyChangedListener(PropertyChangedListener $listener = null)
    {

    }

    public function _onPropertyChanged($propName, $oldVal, $newVal)
    {

    }
}