<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity;

use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
use Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareInterface;
use Doctrine\Common\PropertyChangedListener;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\ORM\Repository\ConcertoTestSoloistLinkingEntityRepository")
 * @ORM\Table(name="concerto_link")
 */
class ConcertoTestSoloistLinkingEntity implements SoloistAwareInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=64)
     */
    protected $domain;

    /**
     * @ORM\Column(name="client_id", type="integer")
     */
    private $clientId;

    /**
     * @ORM\ManyToOne(targetEntity="ConcertoTestSoloist", inversedBy="linkers")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $soloist;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @var ConcertoTestSoloist
     */
    public function setSoloist(Soloist $soloist)
    {
        $this->soloist   = $soloist;
        //$this->clientId  = $soloist->getId();
    }

    /**
     * @var ConcertoTestSoloist
     */
    public function getSoloist()
    {
        return $this->soloist;
    }

    /** @param PropertyChangedListener $listener */
    public function addPropertyChangedListener(PropertyChangedListener $listener)
    {

    }
}
