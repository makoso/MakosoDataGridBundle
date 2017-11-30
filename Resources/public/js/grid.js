var Grid = {
    init: function (gridFormSelector) {
        Grid.initSorting(gridFormSelector);
        Grid.initChanges(gridFormSelector);
        Grid.initSubmit(gridFormSelector);
        Grid.initFilters(gridFormSelector);
        Grid.initReset(gridFormSelector);
    },
    initSorting: function (gridFormSelector) {
        body.on('click', gridFormSelector + ' .sortable', function () {
            var $sortSelector = $(this).closest(gridFormSelector).attr('name') + '_sort_' + $(this).attr('data-grid-column');
            var $sorter = $('#' + $sortSelector);

            if ($sorter.val() === 'ASC') {
                $sorter.val('DESC')
            } else if ($sorter.val() === 'DESC') {
                $sorter.val('')
            } else {
                $sorter.val('ASC')
            }
            $sorter.change();
        });
    },
    initChanges: function (gridFormSelector) {
        body.on('change', gridFormSelector + ' input[type="hidden"]', function () {
            $(this).closest(gridFormSelector).submit();
        });

    },
    initSubmit: function (gridFormSelector) {
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
                $.ajax({
                    url: $form.attr('action'),
                    data: $formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (data) {
                        $form.replaceWith($('<div></div>').append(data).find('form[name="' + $form.attr('name') + '"]'));
                        if (!$hasBox) {
                            $form.removeClass('box');
                        }
                        $(gridFormSelector + ' select.grid-filter-select').change();
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

    },
    initFilters: function (gridFormSelector) {
        body.on('change', gridFormSelector + ' select.grid-filter-select', function () {
            var $inputWrapper1 = $(this).closest('.filterable').find('.grid-filter-input').closest('.input-group');
            var $inputWrapper2 = $(this).closest('.filterable').find('.grid-filter-input2').closest('.input-group');
            var $needSecondInput = $(this).find('option[value="' + $(this).val() + '"]').attr('data-second-input') == "true";

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
        });
        $(gridFormSelector + ' select.grid-filter-select').change();
    },
    initReset: function (gridFormSelector) {
        body.on('click', gridFormSelector + ' .grid-reset', function(){
            var form = $(this).closest('form');

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
                $(this).prop('selectedIndex', 0).val("");
            });

            form.find('textarea').each(function(){
                $(this).val('');
                $(this).html('');
            });

            form.submit();
            return false;
        });
    }
};