# Kilka gridów z tego samego entity na jednej stronie

Czasami bywa tak że potrzebujesz wyświetlić kilka gridów w zależności od jedngo
 parametru, ten grid Ci to umożliwa w dosyć prosty sposób.
 
Korzystając z przykładu który jest tutaj: [Przykład wykorzystania](baseExample.md)
Rozbudujeby go o kolejny konfig, zmienimy tylko nazwę oraz przeciążymy query:

```php
<?php

namespace App\Grid;

use Doctrine\ORM\QueryBuilder;
use Makoso\DatagridBundle\Grid\Grid;

class ExampleAnotherGrid extends ExampleGrid
{
    protected $name = 'example_grid2';

    public function manipulateQuery(QueryBuilder $queryBuilder):void
    {
        $queryBuilder->where($queryBuilder->expr()->neq(Grid::GRID_QUERY_ALIAS.'.firstName', ':first_name'))->setParameter(':test','Jon');
    }
}
```

Teraz wystarczy że wrócisz do kontrolera i wywołasz drugi grid:


```php
<?php

namespace AppBundle\Controller;

use AppBundle\Grid\ExampleGrid;
use AppBundle\Grid\ExampleAnotherGrid;
use Makoso\DatagridBundle\Grid\Grid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Grid $grid)
    {
        return $this->render('default/index.html.twig', [
            'grid' => $grid->configure(new ExampleGrid())->getGrid(),
            'grid2' => $grid->configure(new ExampleAnotherGrid())->getGrid(),
        ]);
    }
}

```

powyższy przykład zadziała bez kolizji ponieważ Grid::configure() zwróci zawsze nową instancję grida.