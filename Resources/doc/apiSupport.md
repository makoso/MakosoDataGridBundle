# Api support - JSON Response

Jeżeli potrzebujesz możesz otrzemać rezultat w formacie JSON, zostanie zwrócony obiekt 
z polami:

`formKey` - klucz którego powinieneś użyć jeżeli będziesz chciał filtrować wynik

`totalPages` - Ostatnia dostępna strona

`currnetPage` - Aktualnie pobrana strona

`data` - Tablica zawierająca obiekty zgodne z konfiguracją grida

### Przykład

Konfiguracja pochodzi z [Przykład wykorzystania](baseExample.md) zmieni się tylko akcja w kontrolerze


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
        return $grid->configure(new VirtualEntityGrid())->getJsonResponse();
    }
}

```

Gotowe!