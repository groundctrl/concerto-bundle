<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity;

use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
use Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="concerto_bad")
 */
class ConcertoTestBadAwareEntity extends ConcertoTestUnawareEntity implements SoloistAwareInterface
{
	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;

	public function setSoloist(Soloist $soloist)
	{

	}
} 