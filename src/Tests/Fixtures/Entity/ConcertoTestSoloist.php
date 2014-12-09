<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity;

use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="concerto_soloist")
 */
class ConcertoTestSoloist implements Soloist
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
     * @ORM\OneToMany(targetEntity="ConcertoTestSoloistLinkingEntity", mappedBy="soloist")
     */
    private $linkers;

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
}
