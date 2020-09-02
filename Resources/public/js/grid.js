var Grid = {
    init: function (parameters) {
        var {
            gridFormSelector,
            beforeSorterPass,
            afterSorterPass,
            afterSorterChange,
            beforeHiddenInputChange,
            beforeFormSubmit,
            afterFormSubmitted,
            afterContentChanged,
            beforeFilterChange,
            afterFilterChange,
            beforeFormReset,
            initSorting,
            initChanges,
            initSubmit,
            initFilters,
            initReset
        } = parameters;
        Grid.initSorting(
            gridFormSelector,
            beforeSorterPass,
            afterSorterPass,
            afterSorterChange,
            initSorting
        );
        Grid.initChanges(
            gridFormSelector,
            beforeHiddenInputChange,
            initChanges
        );
        Grid.initSubmit(
            gridFormSelector,
            beforeFormSubmit,
            afterFormSubmitted,
            afterContentChanged,
            initSubmit
        );
        Grid.initFilters(
            gridFormSelector,
            beforeFilterChange,
            afterFilterChange,
            initFilters
        );
        Grid.initReset(
            gridFormSelector,
            beforeFormReset,
            initReset
        );
    },
    initSorting: function (gridFormSelector, beforeSorterPass, afterSorterPass, afterSorterChange, initSorting) {
        body.on('click', gridFormSelector + ' .sortable', function () {
            var $sortSelector = $(this).closest(gridFormSelector).attr('name') + '_sort_' + $(this).attr('data-grid-column');
            var $sorter = $('#' + $sortSelector);
            if(typeof beforeSorterPass === "function"){
                beforeSorterPass($sorter)
            }
            if ($sorter.val() === 'ASC') {
                $sorter.val('DESC')
            } else if ($sorter.val() === 'DESC') {
                $sorter.val('')
            } else {
                $sorter.val('ASC')
            }
            if(typeof afterSorterPass === "function"){
                afterSorterPass($sorter)
            }
            $sorter.change();
            if(typeof afterSorterChange === "function"){
                afterSorterChange($sorter)
            }
        });

        if(typeof initSorting === "function"){
            initSorting(gridFormSelector);
        }
    },
    initChanges: function (gridFormSelector, beforeHiddenInputChange, initChanges) {
        body.on('change', gridFormSelector + ' input[type="hidden"]', function () {
            if(typeof beforeHiddenInputChange === "function"){
                beforeHiddenInputChange($(this))
            }
            $(this).closest(gridFormSelector).submit();
        });
        body.on('change', gridFormSelector + ' select.grid-filter-input', function () {
            $(this).closest(gridFormSelector).submit();
        });

        if(typeof initChanges === "function"){
            initChanges(gridFormSelector);
        }
    },
    initSubmit: function (gridFormSelector, beforeFormSubmit, afterFormSubmitted, afterContentChanged, initSubmit) {
        body.on('submit', gridFormSelector, function (e) {
            var $form = $(this);
            if ($form.attr('data-no-ajax') != "false") {
                var $hasBox = $form.hasClass('box');
                if (!$hasBox) {
                    $form.addClass('box');
                }
                e.stopPropagation();
                e.preventDefault();
                var $formData = new FormData(this);
                $form.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                if(typeof beforeFormSubmit === "function"){
                    beforeFormSubmit($form, $formData, e);
                }
                $.ajax({
                    url: $form.attr('action'),
                    data: $formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (data) {
                        if(typeof afterFormSubmitted === "function"){
                            afterFormSubmitted($form, data, e);
                        }
                        $form.replaceWith($('<div></div>').append(data).find('form[name="' + $form.attr('name') + '"]'));
                        if (!$hasBox) {
                            $form.removeClass('box');
                        }
                        $(gridFormSelector + ' select.grid-filter-select').change();
                        if(typeof afterContentChanged === "function"){
                            afterContentChanged($form, data, e);
                        }
                    }
                });
                return false;
            }
        });
        body.on('keydown', gridFormSelector + ' input', function (e) {
            if (e.keyCode == 13) {
                $(this).closest('form').submit();
                return false;
            }
        });

        if(typeof initSubmit === "function"){
            initSubmit(gridFormSelector);
        }
    },
    initFilters: function (gridFormSelector, beforeFilterChange, afterFilterChange, initFilters) {
        body.on('change', gridFormSelector + ' select.grid-filter-select', function () {
            var $inputWrapper1 = $(this).closest('.filterable').find('.grid-filter-input').closest('.input-group');
            var $inputWrapper2 = $(this).closest('.filterable').find('.grid-filter-input2').closest('.input-group');
            var $needSecondInput = $(this).find('option[value="' + $(this).val() + '"]').attr('data-second-input') == "true";
            if(typeof beforeFilterChange === "function"){
                beforeFilterChange($(this), $needSecondInput, $inputWrapper1, $inputWrapper2);
            } else {
                if ($(this).val() == "") {
                    $inputWrapper1.hide();
                    $inputWrapper2.hide();
                } else {
                    $inputWrapper1.show();
                    if ($needSecondInput) {
                        $inputWrapper2.show();
                    } else {
                        $inputWrapper2.hide();
                    }
                }
            }

            if(typeof afterFilterChange === "function"){
                afterFilterChange($(this), $needSecondInput, $inputWrapper1, $inputWrapper2);
            }
        });
        $(gridFormSelector + ' select.grid-filter-select').change();

        if(typeof initFilters === "function"){
            initFilters(gridFormSelector);
        }
    },
    initReset: function (gridFormSelector, beforeFormReset, initReset) {
        body.on('click', gridFormSelector + ' .grid-reset', function(){
            var form = $(this).closest('form');
            if(typeof beforeFormReset === "function"){
                beforeFormReset($(this), form);
            }
            form.find('input').each(function(){
                switch($(this).attr('type')){
                    case 'text':
                        $(this).val('');
                        break;
                    case 'radio':
                    case 'checkbox':
                        $(this).prop('checked', false);
                }
            });

            form.find('select').each(function(){
                if(!$(this).hasClass('not-clear')) {
                    $(this).prop('selectedIndex', 0).val("");
                } else {
                    $(this).prop('selectedIndex', 1).val(0);
                }
            });

            form.find('textarea').each(function(){
                $(this).val('');
                $(this).html('');
            });

            form.submit();
            return false;
        });

        if(typeof initReset === "function"){
            initReset(gridFormSelector);
        }
    }
};