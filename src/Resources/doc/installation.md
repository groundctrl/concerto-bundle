# Installation

Just require Concerto in Composer.

```yml
//composer.json

"require": {

        //...
        
        "ctrl/concerto-bundle": "0.8.*"
        
        //...
}
```

And register the bundle in your app

```php
// app/YourAppKernel.php

// ...
class YourAppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = array(
            // ...,
            new Ctrl\Bundle\ConcertoBundle\CtrlConcertoBundle(),
        );

        // ...
    }
}
```

After that, you can [configure it](configuration.md).
