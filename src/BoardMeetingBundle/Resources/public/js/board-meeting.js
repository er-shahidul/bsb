(function ($) {
    var createUrl;

    $(".collection-form-control").on("switchChange.bootstrapSwitch", ".chairman-type-check", function (event, state) {
        $('.chairman-type-check').bootstrapSwitch('state', false, true);
        $(this).bootstrapSwitch('state', true, true);
    });

    $('body').on('board-meeting-form.submit', function (event, form) {
        var modalDiv = $(form).closest('.modal');


        if ($(".chairman-type-check:checked").length < 1) {
            bootbox.alert({title: "Error!!", message: "You must define member(s) and set a chairman!"});
            event.preventDefault();
            return;
        }

        if(modalDiv.length === 0) {
            return;
        }

        event.preventDefault();

        App.blockUI({target: form, overlayColor: "none", animate: true});

        $.post(createUrl, $(form).serialize(), function (data) {

            if (!data.success) {
                bootbox.alert({title: "Error!!", message: data.msg});

                return;
            }
            modalDiv.modal('hide');

            if (data.url) {
                setTimeout(function () {
                    location.replace(data.url);
                }, 2000);
            }

        }, 'json').always(function () {
            App.unblockUI(form);
        });
    });

    window.BoardMeeting = function () {
        function init(url) {
            createUrl = url;
        }

        return {
            init: init
        }
    }();

})(window.jQuery);