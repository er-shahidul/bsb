var MiscPayment = function(){

    toastr.options = {"positionClass": "toast-top-full-width"};

    function rowTotal() {
        var total = 0;
        $('.amount').each(function(){
            total += parseFloat($(this).val() || 0);
        });
        $('.voucher-total').text(custom_number_format(total));
    }

    function validateForm() {
        $('body').on('misc-entry-form.submit', function(){
            var total = 0;

            var container = $('#voucher-detail');
            for (var k in headAmount) {
                if (headAmount.hasOwnProperty(k)) {

                    var index = Object.keys(headAmount).indexOf(k);
                    var fundHeadInput = container.find('.amount:eq('+index+')');
                    var fundHeadValue = fundHeadInput ? parseFloat(fundHeadInput.val()) : 0;

                    total += fundHeadValue;

                    if (headAmount[k].balance < fundHeadValue) {
                        toastr['error'](headAmount[k].name + ' amount should be lower from current balance', "Error");
                        return false;
                    }
                }
            }

            if (total === 0) {
                $('.amount:eq(0)').focus();
                toastr['error']('At least one amount is require', "Error");
                return false;
            }
        });
    }

    function init() {
        $('.amount').keyup(rowTotal).trigger('keyup');
    }

    var headAmount;
    function setHeadAmount(values){
        headAmount = values;
    }

    return {
        init: init,
        setHeadAmount: setHeadAmount
    }
}();