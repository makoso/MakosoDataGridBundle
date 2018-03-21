# Konfiguracja grida JS

Sprawdź czy zaimportowałeś plik z js:
```twig
<script src="/bundles/makosodatagrid/js/grid.js"></script>
```

lub

```twig
<script src="{{ asset('bundles/makosodatagrid/js/grid.js') }}"></script>
```

Aby grid mógł działać musisz go zainicjować:

```js
$(function(){
    Grid.init({
      gridFormSelector: 'form.dataGrid',
      afterContentChanged: function($form, data, e){
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
      },
      initChanges: function(sekector){
          body.on('ifChecked', sekector + ' .grid-pagination input:radio', function () {
              $(this).change();
              return false;
          });
      }
    });
});
```

Powyższy kod zawiera przykład gdy chcemy wykorzystać np bibliotekę iCheck dla łądniejszej paginacji, pełna lista dostępnych parametrów konfiguracyjnych dostępna poniżej:

### ===Ogólne===

gridFormSelector `string` - selektor wykorzystywany do odnalezienia formularzy z gridem

### ===Sortowanie===:

### initSorting(gridFormSelector) `callback`

Parametry:

`gridFormSelector` - selector przekazany w parametrze `gridFormSelector`

Uruchomienie:

Uruchamia się w momencie zakończenia inicjalizowania sortowania

### beforeSorterPass($sorter) `callback`

Parametry:

`$sorter` - input przechowujący wartości dotyczące sortowania

Uruchomienie:

Uruchamia się gdy przycisk sortowania zostanie kliknięty ale wartość sortująca nie zostanie zmieniona

### afterSorterPass($sorter) `callback`

Parametry:

`$sorter` - input przechowujący wartości dotyczące sortowania

Uruchomienie:

Uruchamia się gdy wartość sortująca zostanie zmieniona

### afterSorterChange($sorter) `callback`

Parametry:

`$sorter` - input przechowujący wartości dotyczące sortowania

Uruchomienie:

Uruchamia się gdy wartość sortująca zostanie zatwierdzona w formularzu

### ===Rejestrowanie zmian===

### initChanges(gridFormSelector) `callback`

Parametry:

`gridFormSelector` - selector przekazany w parametrze `gridFormSelector`

Uruchomienie:

Uruchamia się w momencie zakończenia inicjalizowania zmian

### beforeHiddenInputChange(first) `callback`

Parametry:

`first` - selector przekazany w parametrze `gridFormSelector`

Uruchomienie:

Uruchamia się gdy wartość inputa o typie hidden zostanie zmieniona

### ===Wysyłanie formularza===

### initSubmit(gridFormSelector) `callback`

Parametry:

`gridFormSelector` - selector przekazany w parametrze `gridFormSelector`

Uruchomienie:

Uruchamia się w momencie zakończenia inicjalizowania wysyłania

### beforeFormSubmit($form, $formData, e) `callback`

Parametry:

`$form` - element formularza

`$formData` - obiekt klasy FormData zawierający dane które zostaną wysłana ajaxem

`e` - event dostępny podczas akcji submit() na formularzu

Uruchomienie:

Uruchamia się gdy dane są przygotowane do wysłania

### afterFormSubmitted($form, data, e) `callback`

Parametry:

`$form` - element formularza

`data` - response zwrócony ajaxem

`e` - event dostępny podczas akcji submit() na formularzu

Uruchomienie:

Uruchamia się gdy dane zostaną odebrane z serwera

### afterContentChanged($form, data, e) `callback`

Parametry:

`$form` - element formularza

`data` - response zwrócony ajaxem

`e` - event dostępny podczas akcji submit() na formularzu

Uruchomienie:

Uruchamia się gdy kontent zostanie zamieniony

### ===Filtrowanie===

### initFilters(gridFormSelector) `callback`
               
Parametry:
               
`gridFormSelector` - selector przekazany w parametrze `gridFormSelector`

Uruchomienie:

Uruchamia się w momencie zakończenia inicjalizowania filtrowania

### beforeFilterChange(first, $needSecondInput, $inputWrapper1, $inputWrapper2) `callback`
               
Parametry:
               
`first` - selector który zmienił user

`$needSecondInput` - true/false w zależności czy dany filtr wymaga drugiego inputa

`$inputWrapper1` - wrapper inputa numer 1

`$inputWrapper2` - wrapper inputa numer 2

Uruchomienie:

Uruchamia się gdy użytkownik zmieni filtr ale nie zostaną podjęte jeszcze żadne decyzje

### afterFilterChange(first, $needSecondInput, $inputWrapper1, $inputWrapper2) `callback`
               
Parametry:
               
`first` - selector który zmienił user

`$needSecondInput` - true/false w zależności czy dany filtr wymaga drugiego inputa

`$inputWrapper1` - wrapper inputa numer 1

`$inputWrapper2` - wrapper inputa numer 2

Uruchomienie:

Uruchamia się gdy użytkownik zmieni filtr i zostaną wykonane bazowe operacje(jeszcze przed wysłaniem formularza)

### ===Resetowanie===

### initReset(gridFormSelector) `callback`
             
Parametry:
             
`gridFormSelector` - selector przekazany w parametrze `gridFormSelector`

Uruchomienie:

Uruchamia się w momencie zakończenia inicjalizowania resetowania

### beforeFormReset(first, form) `callback`
             
Parametry:
             
`first` - przycisk który kliknął użytkownik
             
`form` - formularz który zostanie wyczyszczony

Uruchomienie:

Uruchamia się gdy użytkownik kliknie przycisk wyczyszczenia grida ale formularz nie zostanie jeszcze wyczyszczony

