(function ($) {
    "use strict";

    function initInputmask() {
        /**
         * $('.amount').inputmask({
            groupSeparator: ",",
            alias: "numeric",
            placeholder: "0",
            autoGroup: !0,
            digits: 2,
            digitsOptional: !1,
            clearMaskOnLostFocus: !1,
            allowMinus: false
        });
         */
        $('.amount').inputmask("decimal", {removeMaskOnSubmit: false, allowMinus: false});
        $('.integer').inputmask("integer", {removeMaskOnSubmit: false});
    }

    function initConfirmationLink() {
        $('body').on('click', '.confirmation-btn', function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var msg = $(this).attr('data-title') || 'Are you sure?';

            if ($(this).is('input') && $(this).parents().is("form")) {
                var form = $(this).parents('form');

                bootbox.confirm(msg, function(result) {
                    if (result) {
                        form.submit();
                    }
                });

            } else {
                bootbox.confirm(msg, function(result) {
                    if (result) {
                        document.location.href = url;
                    }
                });
            }

            return false;
        });
    }

    function initDateField() {
        if (jQuery().datepicker) {
            $('.date-picker, .input-daterange').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayBtn: "linked"
            });
        }
    }

    function initBootstrapSelect() {
        $('.bs-select').selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-check'
        });
    }
    var removeCollectionEntry = function (tr) {
        var $table = tr.closest('table');
        tr.remove();
        reDrawSerialNumbers($table);
    };
    var initDeleteButton = function (parent) {
        parent.on('click', 'a.remove', function (e) {
            e.preventDefault();
            var $el = $(this);
            var tr = $el.closest('tr');
            if ($el.data('server')) {
                bootbox.confirm("Are you sure you want to remove this entry!", function (result) {
                    if (result) {
                        removeCollectionEntry(tr);
                    }
                });
            } else {
                removeCollectionEntry(tr);
            }
        });
    };

    var reDrawSerialNumbers = function (table) {
        table.find('.sn').each(function (i, item) {
            $(item).html(i + 1);
        })
    };

    function initCollectionForm() {
        var $collectionElement = $('.collection-form-control');

        $collectionElement.each(function (i, item) {
            var $item = $(item);
            $item.data('index', $item.find('tr').length);
        });

        initDeleteButton($collectionElement);

        $('.add-new-collection-entry').click(function (e) {
            e.preventDefault();
            var $el = $(this);

            var $collectionHolder = $($el.data('ref'));
            var index = $collectionHolder.data('index');
            var newWidget = $collectionHolder.attr('data-prototype');

            newWidget = newWidget.replace(/__name__/g, index);

            $collectionHolder.data('index', index + 1);

            var newTr = $('<tr/>')
                .attr('id', $collectionHolder.attr('id') + '_' + index)
                .data('index', index)
                .html(newWidget);

            newTr.find('.date-picker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayBtn: "linked"
            });

            newTr.find(".make-switch").bootstrapSwitch();

            initDeleteButton(newTr);
            newTr.appendTo($collectionHolder);
            reDrawSerialNumbers($collectionHolder);
        });
    }

    function handleSubmitOnceButton() {
        $('body').on('click', '.submit-once', function(){
            $(this).attr('disabled', true);
            $(this).parents('form')[0].submit();
        });
    }

    $(document).ready(function(){
        initInputmask();
        initConfirmationLink();
        initDateField();
        initBootstrapSelect();
        initCollectionForm();
        handleSubmitOnceButton();
    });

}(window.jQuery));