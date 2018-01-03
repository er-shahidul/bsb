var Amendment = function(){

    function validateForm() {
        $('form.budget-form').submit(function(e){
            e.preventDefault();

            var error = $('.border-red-thunderbird').length;
            if (error) {
                bootbox.alert('Input request amount.');
            } else {
                $(this)[0].submit();
            }
        });
    }

    function validateInput() {
        $('.amount').on('keyup blur', function(){
            var amount = parseFloat($(this).val());
            var distributed = sanitize_amount($(this).parents('tr').find('.distributed-amount').text());
            $(this).toggleClass('border-red-thunderbird tooltips', (amount < distributed));

            if (amount < distributed) {
                $(this).tooltip({placement: 'left'});
            } else {
                $(this).tooltip('destroy');
            }
            totalAmountValidate();
        }).trigger('blur');
    }

    function totalAmountValidate() {
        var sanctionAmount = sanitize_amount($('.total-sanction-amount').text());
        var totalAmount = sanitize_amount($('.total-amount').text());
        // New amendment total amount greater than old sanction
        if (sanctionAmount > totalAmount) {

        }
        $('.total-amount').toggleClass('bg-red-thunderbird bg-font-red-thunderbird', sanctionAmount > totalAmount);
        $('form.budget-form').find('[type=submit]').attr('disabled', sanctionAmount > totalAmount);
    }

    function init(){
        validateInput();
        validateForm();
        totalAmountValidate();
    }

    return {
        init: init
    }
}();