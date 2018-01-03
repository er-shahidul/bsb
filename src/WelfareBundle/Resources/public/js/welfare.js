$(function () {

    var applicationComments = $("#application-comments");

    applicationComments.on("show.bs.modal", function (e) {
        $('#form-comments').attr('action', $(e.relatedTarget).attr('href'));
    });

    applicationComments.on("shown.bs.modal", function () {
        $('#welfarebundle_application_comment_amount').inputmask("decimal", {removeMaskOnSubmit: false, allowMinus: false});
        $('#welfarebundle_application_comment_microCreditDetail_installmentAmount').inputmask("decimal", {removeMaskOnSubmit: false, allowMinus: false});
    });

    applicationComments.on("hidden.bs.modal", function () {
        applicationComments.find('.modal-content').html('Please wait...');
    });

    $('body').on('submit', '#form-comments', function (e) {

        e.preventDefault();
        $.ajax({
            type: "POST",
            data: $(this).serialize(),
            url: $(this).attr('action'),
            success: function (data) {
                if (data === 'success') {
                    window.location = '/board-meeting/attend';
                    return;
                }
                $('.modal-content').html(data);
            },
            error: function (data) {
                console.log(data); //error message
            }
        });
    });
});