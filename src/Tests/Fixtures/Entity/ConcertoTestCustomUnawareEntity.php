<?php

namespace Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Ctrl\Bundle\ConcertoBundle\Tests\Fixtures\ORM\Repository\ConcertoTestCustomUnawareEntityRepository")
 */
class ConcertoTestCustomUnawareEntity extends ConcertoTestUnawareEntity
{

} 