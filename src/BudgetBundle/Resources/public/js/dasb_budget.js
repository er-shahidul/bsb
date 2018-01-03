var DASBBudget = function(){

    function calculateBudgetTotal() {
        var total = 0;

        $('.budget-form .amount').each(function(){
            total += parseFloat($(this).val() || 0);
        });

        $('.budget-form .total-amount').text(custom_number_format(total));
    }

    function updateInit() {
        $('.budget-form .amount').keyup(calculateBudgetTotal);
        calculateBudgetTotal();
    }
    function init() {

    }

    return {
        init: init,
        updateInit: updateInit
    }
}();