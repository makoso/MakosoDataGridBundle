# Podsumowanie

### Symfony < 3.4

```bash
composer req makoso/data-grid-bundle
```

Włącz bundle

```php
<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            //...
            new AppBundle\AppBundle(),
            new Makoso\DatagridBundle\MakosoDatagridBundle(),
        ];
        
        //...

        return $bundles;
    }
}

```

```bash
./bin/console assets:install
```

Gotowe!


### Symfony >= 3.4

```bash
composer req makoso/data-grid-bundle
```

Gotowe Symfony Flex wykona dalszą pracę, włączy bundle i zainstaluje assety