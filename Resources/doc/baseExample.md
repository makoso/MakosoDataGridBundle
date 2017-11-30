# Przykład wykorzystania

Załóżmy że masz entity podobne do tego:

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Example
 *
 * @package AppBundle\Entity
 * @author  Krzysztof Makowski <kontakt@krzysztof-makowski.pl>
 * @ORM\Entity()
 */
class Example
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $firstName;
    /**
     * @ORM\Column(type="string")
     */
    protected $lastName;

    // getters and setters

}
```

### 1. Stwórz konfigurację grida
Wybierz dowolny namespace w swojej aplikacji gdzie będziesz przechowywał 
konfigurację swoich gridów, ja wybrałem `Grid`

Powinieneś utworzyć nową klasę o dowolnej nazwie rozszerzającą klasę `Makoso\DatagridBundle\Grid\GridConfigurator`
`MUSISZ` zdefiniować w niej dwa pola `$entityClass` entity które będzie bazą do utworzenia query oraz `$name` które będzie
wykorzystane do w kilku miejscach, najważniejsze to nazwa formularza, jeżeli będziesz miał unikalne nazwy swoich konfiguracji 
będziesz mógł wykorzystać kilka gridów na jedenj stronie i nie będą ze sobą kolidowały

`MUSISZ` także zdefiniować kolumny które będą w Twoim gridzie, możesz ustawić je w konstruktorze,
 lub w każdy inny sposób, ważne aby pole `$columns` zawierało kolumny:
 
 ```php
<?php
namespace AppBundle\Grid;


use AppBundle\Entity\Example;
use Makoso\DatagridBundle\Grid\Column\GridColumn;
use Makoso\DatagridBundle\Grid\Filter\StringGroupFilter;
use Makoso\DatagridBundle\Grid\Grid;
use Makoso\DatagridBundle\Grid\GridConfigurator;

class ExampleGrid extends GridConfigurator
{
    protected $entityClass = Example::class;
    protected $name = 'example_grid';
    protected $perPage = 2;

    public function __construct()
    {
        parent::__construct();

        $this->columns->add(
            new GridColumn(
                'first_name',
                Grid::GRID_QUERY_ALIAS.'.firstName',
                null,
                true,
                true,
                new StringGroupFilter()
            )
        );
        $this->columns->add(
            (new GridColumn(
                'last_name',
                Grid::GRID_QUERY_ALIAS.'.lastName'
            ))->setFilterGroup(new StringGroupFilter())
        );
    }
}
```

w ten sposób utworzyliśmy najprostszą konfigurację dla naszego grida.

### 2. Wykonaj akcję w kontrolerze

Wystarczy pobrać serwis `Makoso\DatagridBundle\Grid\Grid` wywołać metodę `configure` oraz `getGrid` 
jako rezultat otrzymamy ten sam obiekt serwisu

```php
<?php

namespace AppBundle\Controller;

use AppBundle\Grid\ExampleGrid;
use Makoso\DatagridBundle\Grid\Grid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $grid = $this->get(Grid::class);
        return $this->render('default/index.html.twig', [
            'grid' => $grid->configure(new ExampleGrid())->getGrid(),
        ]);
    }
}

```

### 3. Zdecyduj z jakiego widoku korzystasz

Możesz wykorzystać bazowy szablon który współpracuje bardzo dobrze z AdminLTE
```twig
{% include 'MakosoDatagridBundle::MyGridTemplate.html.twig' with {grid: grid} %}
```

gdy zajrzysz do tego pliku zobaczysz że jest on bardzo prosty dlatego zachęcam do tworzenia
swojego szablonu, pozwoli Ci to na przeciążenie bazowych bloków

```twig
{% use '@MakosoDatagrid/grid.html.twig' %}

<div class="box">
    <div class="box-body">
        {{ block('gridTable') }}
    </div>
</div>
```

Finalny bazowy szablon zapewniający prawidłowe działanie grid-a(bez AdminLTE) wygląda następująco:
```twig
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Example</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="{{ asset('bundles/makosodatagrid/css/grid.css') }}" />
    </head>
    <body>
    {% include 'MakosoDatagridBundle::MyGridTemplate.html.twig' with {grid: grid} %}

    <script
            src="http://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ asset('bundles/makosodatagrid/js/grid.js') }}"></script>
    <script>
        $(function(){
            Grid.init('form.dataGrid');
        });
    </script>
    </body>
</html>

```

gotowe powinieneś otrzymać słabo ostylowaną tabelkę