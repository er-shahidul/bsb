var BudgetExpense = function(){

    function calculateTotal() {
        var total = 0;

        total += parseFloat($('#budgetbundle_budgetexpensesanction_amount').val());
        total += parseFloat($('#budgetbundle_budgetexpensesanction_vat').val());
        total += parseFloat($('#budgetbundle_budgetexpensesanction_tax').val());

        $('.total-amount').val(custom_number_format(total));
    }

    function showBudgetHeadExpenseSummary() {
        var budgetAmount = $('.budget-amount');
        var budgetExpense = $('.budget-expense');
        var budgetRemaining = $('.budget-remaining');

        $('#budgetbundle_budgetexpense_budgetHead').change(function(){
            var budgetHeadId = $(this).val();

            if (!budgetHeadId) {
                budgetAmount.text(0);
                budgetExpense.text(0);
                budgetRemaining.text(0);
                return;
            }

            var requestUrl = Routing.generate('budget_expense_summary_of_budget_head', {id: budgetHeadId});
            $.ajax({
                'url': requestUrl,
                success: function(data){
                    window.maxExpense = data.budget - data.expense;
                    budgetAmount.text(custom_number_format(data.budget));
                    budgetExpense.text(custom_number_format(data.expense));
                    budgetRemaining.text(custom_number_format(data.budget - data.expense));

                    unblockUI($('.portlet'));
                },
                beforeSend: function(){
                    blockUI($('.portlet'));
                },
                error: function(){
                    unblockUI($('.portlet'));
                }
            });
        }).trigger('change');
    }

    function init() {
        $('.amount').keyup(calculateTotal).trigger('keyup');
        showBudgetHeadExpenseSummary();
    }

    function filterInit() {

        $('#filter-show').click(function(){
            $('#filter-option-container').slideToggle();
        });


        $('#filter-action').click(function(){

            $('#budgetexpense_datatable').DataTable();

        });

    }

    return {
        init: init,
        filterInit: filterInit
    }
}();