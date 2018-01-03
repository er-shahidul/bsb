var ReportFundReceive = function(){

    toastr.options = {"positionClass": "toast-top-full-width"};

    function init(type) {

        $('#voucher_report_form_fundType').on('change', function(){

            $.ajax({
                url: Routing.generate('account_report_from_to_data', {'fundType': $(this).val(), 'type': type}),
                success: function(data){
                    var toOrFrom =  $('#voucher_report_form_toOrFrom');
                    var against =  $('#voucher_report_form_against');
                    toOrFrom.find('option').remove();
                    against.find('option').remove();

                    for (var i in data.toOrFrom) {
                        if (data.toOrFrom.hasOwnProperty(i)) {
                            $('<option>').val(i).text(i).appendTo(toOrFrom);
                        }
                    }

                    for (var i in data.against) {
                        if (data.against.hasOwnProperty(i)) {
                            $('<option>').val(i).text(i).appendTo(against);
                        }
                    }
                    toOrFrom.selectpicker('refresh');
                    against.selectpicker('refresh');
                }
            });

        });
    }

    return {
        init: init
    }
}();