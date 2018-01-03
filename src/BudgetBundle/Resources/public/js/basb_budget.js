var BASBBudget = function(){

    var distributedTotal, totalAmount, invalidForm = false;

    function budgetHeadTotal() {
        var row = $(this).parents('tr');
        var total = 0;
        row.find('.amount').each(function(){
            total += senitizeInputValue($(this));
        });

        row.find('.distribute-head-total').text(custom_number_format(total));

        budgetDistributeGrandTotal();
    }

    function officeTotal() {
        var total = 0;
        var officeId = $(this).attr('rel');

        $('.amount-'+officeId).each(function(){
            total += senitizeInputValue($(this));
        });

        $('.office-'+officeId+'-total').text(custom_number_format(total));
    }

    function remainingHeadTotal() {
        var row = $(this).parents('tr');

        var allocateAmount = sanitize_amount(row.find('.allocate-head-total').text());
        var distributeAmount = sanitize_amount(row.find('.distribute-head-total').text());
        var remainingAmount = row.find('.distribute-head-remaining');
        if (distributeAmount > allocateAmount) {
            row.addClass('error-budget-remaining');
        } else {
            row.removeClass('error-budget-remaining');
        }
        var color = distributeAmount > allocateAmount ? 'font-red-thunderbird' : '';

        remainingAmount.html('<span class="'+color+'">' + custom_number_format(allocateAmount - distributeAmount) + '</span>');
    }

    function senitizeInputValue(el) {
        if (el.is('input')) {
            return parseFloat(el.val() || 0);
        }

        return parseFloat(sanitize_amount(el.text()));
    }

    function budgetSanctionToDasbInit() {

        var amountFields = $('.amount');
        amountFields.keyup(budgetHeadTotal).keyup(officeTotal).keyup(remainingHeadTotal).trigger('keyup');

        budgetAllocateGrandTotal();
        budgetDistributeGrandTotal();

        budgetDistributeGrandRemaining();
        handleSubmitButton();
        amountFields.keyup(budgetDistributeGrandRemaining).keyup(handleSubmitButton);
    }

    function requestAmountTotal() {
        var rel = $(this).attr('rel');

        var row = $('[rel='+rel+']');
        var total = 0;
        row.each(function(){
            total += senitizeInputValue($(this));
        });

        $('.'+rel).text(custom_number_format(total));
    }

    function budgetCompilationInit() {
        $('.amount').keyup(requestAmountTotal).trigger('keyup');
    }

    function budgetAllocateGrandTotal() {
        var total = 0;
        $('.allocate-head-total').each(function(){
            total += senitizeInputValue($(this));
        });
        totalAmount = total;

        $('.allocate-head-grand-total').text(custom_number_format(total));
    }

    function budgetDistributeGrandTotal() {
        var total = 0;
        $('.distribute-head-total').each(function(){
            total += senitizeInputValue($(this));
        });
        distributedTotal = total;
        $('.distribute-head-grand-total').text(custom_number_format(total));
    }

    function budgetDistributeGrandRemaining() {
        var $distributeRemainingGrandTotal = $('.distribute-head-grand-remaining');

        if (distributedTotal > totalAmount) {
            $distributeRemainingGrandTotal.addClass('error-budget-remaining');
        } else {
            $distributeRemainingGrandTotal.removeClass('error-budget-remaining');
        }
        var color = distributedTotal > totalAmount ? 'font-red-thunderbird' : '';
        $distributeRemainingGrandTotal.html(custom_number_format(totalAmount - distributedTotal));
    }

    function isDistributeGreaterThanAllocate() {
        return distributedTotal > totalAmount;
    }

    function handleSubmitButton() {
        $('button[name=save]').prop('disabled', $('.error-budget-remaining').length);
    }

    return {
        budgetSanctionToDasbInit: budgetSanctionToDasbInit,
        budgetCompilationInit: budgetCompilationInit
    }
}();