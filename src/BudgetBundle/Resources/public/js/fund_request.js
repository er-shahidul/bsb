var FundRequest = function(){

    function addBudgetHeadHandler() {
        var $collectionHolder;

        // Get the ul that holds the collection of tags
        $collectionHolder = $('#budgetDetailsContainer');

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find('tr').length);

        $('#add-more').on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            if ($('#budget-detail-'+$('#form_budgetHeads').val()).length) {
                bootbox.alert('Selected budget head already exits.');
                return;
            }

            // add a new tag form (see next code block)
            addBudgetDetails($collectionHolder);
        });
    }

    function addBudgetDetails($collectionHolder) {
        var budgetHeadId = $('#form_budgetHeads').val();

        var prototype = $collectionHolder.data('prototype');

        var newForm = prototype.replace(/__head__/g, budgetHeadId);

        $.ajax({
            'url': Routing.generate('budget_head_stats', {id: budgetHeadId}),
            success: function(data){
                $newForm = $(newForm);
                $newForm.find('.sl').text($collectionHolder.find('tr').length + 1);
                $newForm.find('.code').text(data.headCode);
                $newForm.find('.account-head').text(data.headTitle);
                //$newForm.find('.before-prev-head-amount').text(data.beforePrev.amount);
                //$newForm.find('.prev-head-amount').text(data.prev.amount);
                $newForm.find('.head-amount').text(data.current.amount);
                $newForm.find('.head-amendment').text(data.revise.amount);
                $newForm.find('.expense').text(data.expense);

                $newForm.find('.amount input').inputmask("decimal", {removeMaskOnSubmit: false, allowMinus: false});
                $collectionHolder.append($newForm);

                unblockUI($('.portlet'));
            },
            beforeSend: function(){
                blockUI($('.portlet'));
            },
            error: function(){
                unblockUI($('.portlet'));
            }
        });
    }

    function deleteRow() {
        $('#budgetDetailsContainer').on('click', '.fa-close', function(){
            var row = $(this).parents('tr');
            bootbox.confirm('Do you want to delete this row?', function(result){
                if (result) {
                    row.remove();
                    setSlNumber();
                }
            })
        });
    }

    function setSlNumber() {
        var sl = 1;
        $('#budgetDetailsContainer').find('tr:not(.no-record)').each(function(){
            $(this).find('.sl').text(sl);
            sl++;
        });
    }

    function validateAllocationAmount() {
        var row = $(this).parents('tr');

        var remainingAmount = sanitize_amount(row.find('.remaining-amount').text());
        if (parseFloat($(this).val()) > remainingAmount) {
            row.addClass('error-budget-remaining');
        } else {
            row.removeClass('error-budget-remaining');
        }
        handleAllocationSubmitButton()
    }

    function handleAllocationSubmitButton() {
        $('button[name=save]').prop('disabled', $('.error-budget-remaining').length);
    }

    function handleFundRequestSubmitButton() {
        $('#budgetDetailsContainer').bind("DOMSubtreeModified",function(){
            var rows = $('#budgetDetailsContainer').find('tr:not(.no-record)').length;
            $('button[name=save]').prop('disabled', !rows);

            if (rows) {
                $('.no-record').hide();
            } else {
                $('.no-record').show();
            }
            console.log('dom change');
        }).trigger('DOMSubtreeModified');
    }

    function init() {
        addBudgetHeadHandler();
        deleteRow();
        handleFundRequestSubmitButton();

        $('#budgetDetailsContainer').on('blur', 'input.amount', function(){
            var value = parseFloat($.trim($(this).val()));
            if (!value) {
                $(this).addClass('border-red-thunderbird');
            } else {
                $(this).removeClass('border-red-thunderbird');
            }
        });

        $('form.fund-request-form').submit(function(e){
            $('#budgetDetailsContainer').find('input.amount').trigger('blur');
            e.preventDefault();
            var error = $('.border-red-thunderbird').length;
            if (error) {
                bootbox.alert('Input request amount.');
            } else {
                $(this)[0].submit();
            }
        });
    }

    function allocationInit() {
        DASBBudget.updateInit();
        $('.amount').keyup(validateAllocationAmount).trigger('keyup');
    }

    return {
        init: init,
        allocationInit: allocationInit
    }
}();