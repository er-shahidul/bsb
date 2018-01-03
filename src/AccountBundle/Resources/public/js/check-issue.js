var CheckIssue = function(){

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
        $('#cheque_issue_form_sanctions').multiSelect();

        $('#voucher-detail').on('click', '.add-voucher', function(){
            var vouchers = $('#voucher-list');
            var row = vouchers.find('tr:last').clone();
            var nextChequeNumber = parseInt(row.find('.cheque-number').val()) ? parseInt(row.find('.cheque-number').val()) + 1 : '';
            row.find('.cheque-number').val(nextChequeNumber);

            vouchers.append(row);
            prepareWidgets(row);

            setSlNumber();

        }).on('click', '.remove', function(e){
            e.preventDefault();
            if ($('#voucher-detail tbody tr').length == 1) {
                toastr['error']('At least one cheque entry is required', "Error");
                return false;
            }

            var row = $(this).parents('tr');
            bootbox.confirm('Are you sure do you want to delete?', function(r){
                if (r) {
                    row.remove();
                    setSlNumber();
                }
            });
        });

        $('#voucher-detail tbody tr').each(function(){
            prepareWidgets($(this));
        });

        $('body').on('cheque-issue-form.submit', function(){

            /*var container = $('#voucher-detail');
            for (k in headAmount) {
                if (headAmount.hasOwnProperty(k)) {
                    var total = 0;
                    $(container.find('tr .amount[data-for='+k+']')).each(function(){
                        total += parseFloat($(this).val());
                    });

                    if (headAmount[k].balance < total) {
                        toastr['error'](headAmount[k].name + ' amount should be lower from current balance', "Error");
                        return false;
                    }
                }
            }*/

            return validateSanctionVoucherAmount();
        });
    }

    function validateSanctionVoucherAmount()
    {
        var sanctions = $('#cheque_issue_form_sanctions');
        var sanctionTotalAmount = 0;
        $.each(sanctions.val(), function(i, value){
            sanctionTotalAmount += parseFloat(sanctions.find('option[value='+value+']').text().split('-')[1]);
        });

        var voucherTotalAmount = 0;
        $('.voucher-total').each(function(){
            voucherTotalAmount += parseFloat(sanitize_amount($(this).text()));
        });

        if (sanctionTotalAmount !== voucherTotalAmount) {
            toastr['error']("Invalid voucher amount", "Error");
            return false;
        }

        return true;
    }

    function setSlNumber() {
        var sl = 1;
        $('#voucher-list').find('tr:not(.no-record)').each(function(){
            $(this).find('.sl').text(sl);
            sl++;
        });
    }

    function prepareWidgets(row)
    {
        var index = $('#voucher-detail tbody tr').length;

        row.find('.date-picker, .input-daterange').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            todayBtn: "linked"
        });
        row.find('.amount').inputmask("decimal", {removeMaskOnSubmit: false, allowMinus: false});

        row.find('.amount').keyup(rowTotal).trigger('keyup');

        row.find('.amount').each(function(){
            $(this).attr('data-num', index);
        });
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