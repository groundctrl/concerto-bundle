# Advanced Example

## Background
Let's go with the same background from the [simple example](example_simple.md), except now your `Retailer`s might have multiple subdomains. Instead of a simple string, these will be `Domain` objects this time around, and THOSE will hold information about the `Buyer`s. This extra separation will require us to use the `RepositorySolo` rather than the `HostnameSolo`.

```php

class User
{
    //...
}
```
```php
/**
 * @ORM\Table(name="retailers")
 * @ORM\Entity(repositoryClass="Your\Bundle\YourBundle\ORM\Repository\RetailerRepository")
 */
class Retailer extends User implements Soloist
{
    /**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /**
     * @var Domain
     *
	 * @ORM\OneToMany(targetEntity="Domain", mappedBy="retailer")
	 */
	private $domains;

    //...
}
```

```php
/**
 * @ORM\Table(name="buyers")
 * @ORM\Entity
 */
class Buyer extends User implements SoloistAwareInterface
{
    /**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /**
	 * @var Retailer
	 *
	 * @ORM\ManyToOne(targetEntity="Retailer")
	 * @ORM\JoinColumn(name="retailer_id", referencedColumnName="id")
	 */
	private $retailer;

    public function setSoloist(Soloist $soloist)
    {
    	$this->retailer = $soloist;
    }

    //...
}
```

Now we have our slightly different `Buyer` and `Retailer`. Note that `Buyer` retains its association with `Retailer`, but `Retailer` no longer holds anything about `Buyer`. Also note that `Retailer` now has an annotation for a custom repository class. Here's `Domain`:

```php
/**
 * @ORM\Table(name="retailer_domains")
 * @ORM\Entity
 */
class Domain implements SoloistAwareInterface
{
    /**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /**
	 * @var Retailer
	 *
	 * @ORM\ManyToOne(targetEntity="Retailer")
	 * @ORM\JoinColumn(name="retailer_id", referencedColumnName="id")
	 */
	private $retailer;

    public function setSoloist(Soloist $soloist)
    {
    	$this->retailer = $soloist;
    }

    //...
}
```
This will allow us to find a `Buyer`'s `Retailer` using a custom repository that's made to work with `SoloInterface::getSoloist(Request $request)`. We'll make that now.

```php
//Your\Bundle\YourBundle\ORM\Repository\RetailerRepository

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * EntityRepository for Retailers
 */
class RetailerRepository extends EntityRepository
{
    public function forRequest(Request $request)
    {
        $domain = $request->getHost();
        $em     = $this->getEntityManager();

        $query = $em->createQuery('SELECT r,d FROM YourYourBundle:Domain d JOIN d.retailer r WHERE d.domain = :domain');
        $query->setParameter('domain', $domain);

        return $query->getResult()[0]->getRetailer();
    }
}
```

The configuration for this setup would look like...

```yml
# config.yml
concerto:
    soloist_class: Your\Bundle\YourBundle\Entity\Retailer
    solo_name: repository
    solos:
        repository:
            arguments:
                - @your_soloist_repository
                - forRequest
```
```yml
# services.yml

services:
	your_soloist_repository:
    	class: Doctrine\ORM\EntityRepository
        factory_service: doctrine.orm.entity_manager
        factory_method: getRepository
        arguments:
        	- %concerto.soloist_class%
```

See also: [simple example](example_simple.md).

Or check out what happens [under the hood](../under_the_hood.md).
