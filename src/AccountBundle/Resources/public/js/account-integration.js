var AccountIntegration = function(){

    toastr.options = {"positionClass": "toast-top-full-width"};

    function rowTotal() {
        var row = $(this).parents('tr');
        var total = 0;
        row.find('.amount').each(function(){
            total += parseFloat($(this).val() || 0);
        });
        row.find('.voucher-total').text(custom_number_format(total));
    }

    function init() {

        $('body').on('form-integration.submit', function(){
            var valid = true;
            $('.voucher-total').each(function(){
                if (sanitize_amount($(this).text()) !== parseFloat($(this).data('amount'))) {
                    toastr['error']('Amount not match', "Error");
                    valid = false;
                    return false;
                }
            });

            return valid;
        });

        $('.amount').keyup(rowTotal).trigger('keyup');
    }

    return {
        init: init
    }
}();