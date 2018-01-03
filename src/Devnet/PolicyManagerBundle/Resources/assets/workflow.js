(function ($) {
    "use strict";

    function handleWorkflowAction(result, url, redirect, entity) {
        App.blockUI({message: 'Processing...', animate: true});
        $.post(url, {step_remark: result, entity: entity}, function (data) {
            location.href = redirect;
        })
    }

    $('body').on('click', '.workflow-action', function () {
        var $el = $(this);

        var title = 'Comments';//$el.data('workflow-title');
        var url = $el.data('workflow-url');
        var redirect = $el.data('workflow-redirect');
        var entity = $el.data('workflow-entity');

        bootbox.prompt({
            title: title,
            inputType: 'textarea',
            callback: function (result) {
                if(result === null) {
                    return null;
                }
                handleWorkflowAction(result, url, redirect, entity);
            }
        });
    })

}(window.jQuery));