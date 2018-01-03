var ReceivePayment = function(){

    toastr.options = {"positionClass": "toast-top-full-width"};

    function rowTotal() {
        var total = 0;
        $('.amount').each(function(){
            total += parseFloat($(this).val() || 0);
        });
        $('.voucher-total').text(custom_number_format(total));
    }

    function validateForm() {
        $('body').on('receive-entry-form.submit', function(){
            var total = 0;

            $('.amount').each(function(){
                total += parseFloat($(this).val());
            });

            if (total === 0) {
                $('.amount:eq(0)').focus();
                toastr.clear();
                toastr['error']('At least one amount is require', "Error");
                return false;
            }
        });
    }

    function init() {

        $('.amount').keyup(rowTotal).trigger('keyup');
        validateForm();
    }

    return {
        init: init
    }
}();