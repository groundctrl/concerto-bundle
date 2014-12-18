# Getting Started

1. [Install it](installation.md).

- [Configure it](configuration.md).
- Implement `Soloist` on your tenant class

  ```php
  //Your\Bundle\YourBundle\YourTenantEntity;
  
  use Ctrl\Bundle\ConcertoBundle\Model\Soloist;
  //...
  
  /**
   * YourTenantEntity
   *
   * @ORM\Table(name="tenants")
   * @ORM\Entity
   */
  class YourTenantEntity implements Soloist
  {      
      //...
  }
  ```
- Implement `SoloistAwareInterface` on everything that needs to know about its `Soloist`

  ```php
  //YourUserEntity
  
  use Ctrl\Bundle\ConcertoBundle\Model\SoloistAwareInterface;
  
  /**
   * YourUserEntity
   * @ORM\Table(name="users")
   * @ORM\Entity
   */
  class YourUserEntity implements SoloistAwareInterface
  {
      //...
  }
  ```
- ????
- Profit!

Also check out the [examples](cookbook/examples.md).