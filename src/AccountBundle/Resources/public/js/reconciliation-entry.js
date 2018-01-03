var ReconciliationEntry = function(){

    toastr.options = {"positionClass": "toast-top-full-width"};

    function init(){
        validateForm();

        $('#voucher-list').find('input[type=checkbox]').change(function(){
            var datePicker = $(this).parents('tr').find('.date-picker');
            if (datePicker.length) {
                $('td.has-error').removeClass('has-error').find('.help-block-error').remove();
                datePicker.attr('required', $(this).is(':checked'));
            }
        });
    }

    function validateForm() {
        /*$('body').on('reconcile-entry-form.submit', function(){

            if ($('#voucher-list').find('tr').length === 0) {
                toastr.clear();
                toastr['error']('At least one voucher is require', "Error");
                return false;
            }
        });*/
    }

    return {
        init: init
    }
}();