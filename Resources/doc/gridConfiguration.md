# Konfiguracja grida

Konfiguracja odbywa się poprzez przygotowanie klasy rozszerzającej `Makoso\DatagridBundle\Grid\GridConfigurator`
zawiera ona metody pozwalające na przeciążanie niektórych metod jak i modyfikowanie wyświetlanych wartości

Interfejs zawiera następujące metody:

```php
<?php

namespace Makoso\DatagridBundle\Grid;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

abstract class GridConfigurator implements GridConfiguratorInterface
{
    protected $actionColumns;
    protected $columns;
    protected $name;
    protected $entityClass;
    protected $actionColumnOnLeft = false;
    protected $perPage = 5;

    public function __construct()
    {
        $this->actionColumns = new ArrayCollection();
        $this->columns = new ArrayCollection();
    }

    public function getActionColumns():ArrayCollection
    {
        return $this->actionColumns;
    }

    public function getColumns():ArrayCollection
    {
        return $this->columns;
    }

    public function getActionColumnOnLeft():bool
    {
        return $this->actionColumnOnLeft;
    }
    
    public function getEntityClass():string
    {
        if(!$this->entityClass){
            throw new \ErrorException('entityClass must be provided');
        }
        return $this->entityClass;
    }
    
    public function getName():string
    {
        if(!$this->name){
            throw new \ErrorException('name must be provided');
        }
        return $this->name;
    }

    public function titleFormatting(string $title):string
    {
        return $title;
    }

    public function getPerPage():int
    {
        return $this->perPage;
    }

    public function manipulateQuery(QueryBuilder $queryBuilder):void{}
}
```

### Atrybut $name (Wymagane)
<pre>
Określa nazwę grida a także formularza który jest używany do paginacji, sortowania a także filtrowania
</pre>

### Atrybut $entityClass (Wymagane)

<pre>
Określa entity które zostanie użyte w do stworzenia zapytania
</pre>

### Atrybut $perPage (Domyślnie 5)

<pre>
Określa iloś rekordów pobieraną na jednej stronie
</pre>

### Atrybut $actionColumnOnLeft (Domyślnie false)

<pre>
Domyślnie kolumna akcji(jeżeli są ustawione) wyświetlana jest po prawej stronie grida
jeżeli ustawisz ten parametr na true, kolumna akcji będzie wyświetlona z lewej strony
</pre>

### Atrybut $columns oraz metoda getColumns()

<pre>
Masz do wyboru cze modyfikujesz w jakiś sposób obiekt $columns dodając do niego kolumny 
czy czy przeciążasz metodę getColumns i zwracasz nowy obiekt ArrayCollection zawierający konfigurację kolumn
</pre>

### Atrybut $actionColumns oraz metoda getActionColumns()

<pre>
Masz do wyboru cze modyfikujesz w jakiś sposób obiekt $actionColumns dodając do niego akcje 
czy czy przeciążasz metodę getColumns i zwracasz nowy obiekt ArrayCollection zawierający konfigurację akcji możliwych do wykonania na pojedynczym rekordzie
</pre>

### Metoda titleFormatting($title)

<pre>
Pozwala na modyfikację tytułu przekazywanego w konfiguracji kolumn(jeżeli tego nie zrobisz zostanie użyty name kolumny) pozwala to na doklejenie wartości
potrzebnych do zadziałania ogólnie przyjętego standardu budowania kluczy tłumaczeń np:
</pre>
 ```php
 public function titleFormatting(string $title):string
     {
         return 'entity.example.'.$title.'.label';
     }
 ```
 
### Metoda manipulateQuery($queryBuilder)

<pre>
Pozwala na zmianę bazowygo zapytania, jest to przydatne podczas gdy chcemy wyświetlić tylko powiązane rekordy na podglądzie głównego rekordu lub wyświetlić
tylko rekordy spełniające tylko określone nasze zapytanie
np:
</pre>
 
 ```php
public function manipulateQuery(QueryBuilder $queryBuilder):void
    {
        $queryBuilder->where($queryBuilder->expr()->neq(Grid::GRID_QUERY_ALIAS.'.name', ':test'))->setParameter(':test','test');
    }
```