# Simple Example

## Background
Imagine you run online stores. You're more of a middleman-- retailers set up an account with your service and sell their products through a subdomain on your website. Buyers must register in the same way to make a purchase.

This means our `Soloist`s ('tenants') will be `Retailer`s and our `SoloistAwareInterface`s will be `Buyer`s.


```php

class User
{
    //...
}
```
```php
/**
 * @ORM\Table(name="retailers")
 * @ORM\Entity
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
     * @var string
     *
     * @ORM\Column(name="domain", type="string")
     */
    private $domain;
    
    /**
	 * @ORM\OneToMany(targetEntity="Buyer", mappedBy="retailer")
	 */
    private $buyers;
    
    //...
}
```
The `Retailer` class has 3 properties we care about in the case of multi-tenancy: Its own id (gotta save it somehow), its domain (how we will find it), and its buyers. We won't be doing anything directly with `$buyers` (we care about finding the `Retailer` associated with a particular `Buyer`, not the other way around)-- but Doctrine requires One-To-Many / Many-To-One associations to be bidirectional (barring an additional join table annotation-- see [here](http://doctrine-orm.readthedocs.org/en/latest/reference/association-mapping.html)). 

So you have to put the associations on your vars.

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
     * @var string
     *
	 * @ORM\ManyToOne(targetEntity="Retailer", mappedBy="buyers")
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

The configuration for this setup would look like...

```yml
concerto:
    soloist_class: Your\Bundle\YourBundle\Entity\Retailer
    solo_name: hostname
    solos:
        hostname:
            arguments:
                - domain
```

And that's all the setup you would need to do! For a slightly more complex example, see [here](example_advanced.md).

Or check out what happens [under the hood](../under_the_hood.md).

