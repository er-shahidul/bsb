$('form')
    .find('input[type=text], input[type=email], input[type=password], input[type=radio], input[type=checkbox], textarea, select, input[type=number]')
    .blur(function(){
        // Run validation for this field
        removeGeneratedErrorElement($(this));
        var that = $(this);
        if ($(this).hasClass('date-picker')) {
            setTimeout(function(){
                that.jsFormValidator('validate');
            }, 200);
            return;
        }
        that.jsFormValidator('validate');
    })
    .focus(function() {
        // Reset markers when focus on a field
        //$(this).parents('.form-group').removeClass('error');
        //$(this).removeClass('ready');
    })
    .jsFormValidator({
        'showErrors': function(errors, sourceId) {
            if (errors.length) {
                generateErrorElement($(this), errors);
            } else {
                //$(this).parents('.form-group').removeClass('has-error');
            }
        }
    });

function generateErrorElement(elm, error) {
    removeGeneratedErrorElement(elm);

    elm.after('<span class="help-block">' +
        '<ul class="list-unstyled"><li><span class="glyphicon glyphicon-exclamation-sign"></span> '
        + error +
        '</li></ul></span>');
    elm.parents('.form-group').addClass('has-error');
}

function removeGeneratedErrorElement(elm) {
    var errorBlock = elm.next();
    if (errorBlock.hasClass('help-block')) {
        errorBlock.remove();
        elm.parents('.form-group').removeClass('has-error');
    }
}