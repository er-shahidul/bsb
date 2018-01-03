$(function () {

    var paymentDatable = $("#sg-datatables-MCDefaulterPaymentsDatatable_datatable");

    paymentDatable
        .on("click", ".MCDefaulterPaymentsDatatable_datatable_action", function (e) {
            e.preventDefault();
            if (confirm("Are you sure?")) {
                $.get( $(this).attr('href'), function( data ) {
                    window.location = window.location.href;
                });
            }
        });

    $("#form-request-defaulter").on("click", "#btn-request-defaulter", function (e) {
        e.preventDefault();
        if (confirm("Are you sure?")) {
            $.post($(this).attr('data-url'), $( "#form-request-defaulter" ).serialize(), null, 'json').done(function () {
                window.location = window.location.href;
            });
        }
    });

    var addDefaulter = $('#add-defaulter');
    var searchResult = $('#search-result');
    var defaulterSearch = $('#defaulter-search');
    var defaulterList = $('#defaulter-list');

    $("#select-meeting").on("change", function (e) {

        var id = $(this).val();
        if (id) {
            $.get( '/welfare/micro-credit/defaulter-register/defaulter-list/'+id, function( data ) {
                $("#defaulter-search").html(data);
            });
            addDefaulter.css('display', 'block');
            searchResult.css('display', 'block');
            return;
        }
        $("#defaulter-search").html('');
        addDefaulter.css('display', 'none');
        searchResult.css('display', 'none');
    });

    addDefaulter.on("click", function (e) {

        if(!defaulterSearch.find('input[type="checkbox"]:checked').length) {
            alert('Nothing selected');
            return;
        }

        defaulterSearch.find('input[type="checkbox"]:checked').each(function (e) {
            var row = $.trim($(this).closest('tr').html());

            var elem = defaulterList.find('tbody').find('tr').filter(function(){
                return $.trim($(this).html()) === row;
            });

            if (!elem.length) {
                defaulterList.append('<tr>'+row+'</tr>');
            }
        });
    });

    $("#remove-defaulter").on("click", function (e) {

        if(!defaulterList.find('input[type="checkbox"]:checked').length) {
            alert('Nothing selected');
            return;
        }

        defaulterList.find('input[type="checkbox"]:checked').each(function (e) {
            $(this).closest('tr').remove();
        });
    })
});
