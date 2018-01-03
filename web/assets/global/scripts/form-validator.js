(function (factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else {
        factory(window.jQuery);
    }
}(function ($) {
    'use strict';

    window.handleValidation = function($form) {
        console.log('validation handler');

        var error = $('.alert-danger', $form);
        var success = $('.alert-success', $form);

        function shouldContinueRegularFormSubmission($form) {
            var formId = $form.attr('id');
            if (formId) {
                var event = jQuery.Event(formId + '.submit');
                $('body').trigger(event, [$form]);

                if (event.isDefaultPrevented()) {
                    return false;
                }
            }

            return true;
        }

        $form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true,
            ignore: "",

            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("data-error-container")) {
                    var $errorPlacement = $(element.attr("data-error-container"));
                    error.insertAfter($errorPlacement);
                }else if (element.attr("data-error-marker")) {
                    $(element.attr("data-error-marker")).html("");
                    error.insertAfter(element.attr("data-error-marker"));
                } else if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.parents('.radio-list').size() > 0) {
                    error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                } else if (element.parents('.radio-inline').size() > 0) {
                    error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                } else if (element.parents('.checkbox-list').size() > 0) {
                    error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                } else if (element.parents('.checkbox-inline').size() > 0) {
                    error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                var errorEvent = $.Event('jq-validation-error');
                $('body').trigger(errorEvent, [validator]);
                success.hide();
                error.show();
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

            submitHandler: function (formObject) {
                success.show();
                error.hide();

                var form = formObject instanceof jQuery ? formObject[0] : formObject;

                if(shouldContinueRegularFormSubmission($(form))){
                    form.submit(); // submit the form
                }
            }

        });

        //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
        $('.select2me', $form).change(function () {
            $form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });

        $('.date-picker .form-control').change(function() {
            $form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        })
    };

    $('form.jq-validate').each(function(index, el) {
        handleValidation($(el));
    });

}));