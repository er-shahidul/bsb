var QUATERBudget = function(){
    var distributedTotal, totalAmount, invalidForm = false;

    function remainingParentHeadTotal() {
        var row = $(this).parents('tr');
        var parent_id = $(this).data("parent-id");
        var column = $(this).data("column");
        console.log(column)
        var total = 0;
        row.find('.amount').each(function(){
            total += senitizeInputValue($(this))
        });
       var child_row_total = sanitize_amount(row.find('.child-row-total').val())/1000;

        if(total>child_row_total){
            $('.'+parent_id).closest('tr').addClass('error-budget-remaining');
            $('.grand_total_'+column).parent('tr').addClass('error-budget-remaining');
            row.addClass('error-budget-remaining');
        }else {
            row.removeClass('error-budget-remaining');
            $('.'+parent_id).closest('tr').removeClass('error-budget-remaining');
            $('.grand_total_'+column).closest('tr').removeClass('error-budget-remaining');
        }
    }

    function parentHeadTotal() {
        var total = 0;
        var parentHead = $(this).attr('rel');
        $('.amount-'+parentHead).each(function(){
            total += senitizeInputValue($(this));
        });

        $('.total-head-'+parentHead).text( toBangla(custom_number_format(total)));
        $('.total-head-'+parentHead).val(total);
    }

    function columntWiseGrandTotal() {
        var total = 0;
        var column = $(this).data('column');
        $('.column-'+column).each(function(){
            total += senitizeInputValue($(this));
        });

        $('.grand_total_'+column).text( toBangla(custom_number_format(total)));
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

    function quaterWiseBudgetInit() {

        var amountFields = $('.amount');
        amountFields.keyup(parentHeadTotal).keyup(remainingParentHeadTotal).keyup(columntWiseGrandTotal).trigger('keyup');

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

    function toBangla (str)
    {
        //check if the `str` is not string
        if(!isNaN(str)){
            //if not string make it string forcefully
            str = String(str);
        }

        //start try catch block
        try {
            //keep the bangla numbers to an array
            var convert = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            //now split the provided string into array by each character
            var splitArray = str.split("");
            //declare a empty string
            var newString = "";
            //run a loop upto the length of the string array
            for (i = 0; i < splitArray.length; i++) {

                //check if current array element if not number
                if(isNaN(splitArray [i])){
                    //if not number then place it as it is
                    newString += splitArray [i];
                }else{
                    //if number then get same numbered element from the bangla array
                    newString += convert[splitArray [i]];
                }
            }
            //return the newly converted number
            return newString;
        }
        catch(err) {
            //if any error occured while convertion return the original string
            return str;
        }
        //by default return original number/string
        return str;
    }

    return {
        quaterWiseBudgetInit: quaterWiseBudgetInit,
        budgetCompilationInit: budgetCompilationInit
    }
}();