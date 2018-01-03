(function ($) {

    function highlightErrorTabs(errors) {
        var firstError = $(errors[0]['element']).closest('.tab-pane').attr('id');

        if(firstError) {
            $("a[href='#"+ firstError +"']").trigger('click');
        }

        var $links = $("#page-tab-ul").find('a');
        $links.removeClass('has-error');
        $links.each(function (index, item) {
            var $item = $(item);
            for (var i in errors) {
                if (errors.hasOwnProperty(i)) {
                    if ($($item.attr('href')).find(errors[i]['element']).length > 0) {
                        $item.addClass('has-error');
                        break;
                    }
                }
            }
        })
    }

    $('body').on('jq-validation-error', function (event, validator) {
        highlightErrorTabs(validator.errorList);
    });

    var districtEl = $('#personnel_form_district');
    var upazilaOpt = $('#personnel_form_upazila');
    var permanentUpazilaOpt = $('#personnel_form_permanentUpazila');
    var deceasedEl = $("input:radio[name ='personnel_form[deceased]']");
    var disabledEl = $('#personnel_form_disabled');
    var rankEl = $('.personnel-rank-option');
    var corpEl = $('.personnel-corp-option');
    var retirementReasonEl = $('#personnel_form_retirementReason');

    var districtSearch = $('#search_permanentDistrict');
    var upazilaSearch = $('#search_permanentUpazila');

    $(".personnel-service-option").on('change', function () {
        var $el = $(this);
        var serviceId = $el.val();

        if (!serviceId) {
            rankEl.html('<option value="">Select Regiment & Corps</option>');
            corpEl.html('<option value="">Select Rank</option>');
            return true;
        }

        $.get(Routing.generate('personnel_service_child_lookup', {service: serviceId}), function (res) {
            var text = '<option value="">Select Regiment & Corps</option>';
            for (var i = 0; i < res['corps'].length; i++) {
                text += '<option value="' + res['corps'][i].id + '">' + res['corps'][i].name + '</option>';
            }
            corpEl.html(text);
            text = '<option value="">Select Rank</option>';
            for (var i = 0; i < res['ranks'].length; i++) {
                text += '<option value="' + res['ranks'][i].id + '">' + res['ranks'][i].name + '</option>';
            }
            rankEl.html(text);
        })
    });

    $('.district').on('change', function () {
        var $el = $(this);
        var districtId = $el.val();

        var currentEl = null;

        switch ($el.data('upazila')) {
            case 'present' :
                currentEl = upazilaOpt;
                break;
            case 'permanent' :
                currentEl = permanentUpazilaOpt;
                break;
            case 'permanentSearch' :
                currentEl = upazilaSearch;
                break;
            default:
                currentEl = null;
        }

        if (!districtId) {
            currentEl.html('<option value="">উপজেলা</option>');
            return;
        }

        $.get(Routing.generate('personnel_upazila_lookup', {district: districtId}), function (res) {
            var text = '<option value="">উপজেলা</option>';
            for (var i = 0; i < res.length; i++) {
                text += '<option value="' + res[i].id + '">' + res[i].name + '</option>';
            }

            if (currentEl) {
                currentEl.html(text);
            }

        })
    });

    var updateDeceasedDateElement = function (state) {
        if (state === 1) {
            $("#deceasedReason").show();
            $("#personnel_form_deceasedDate").attr('required', 'required');
        } else {
            $("#deceasedReason").hide();
            $("#personnel_form_deceasedDate").removeAttr('required');
        }
    };

    deceasedEl.on('change', function (event) {
        updateDeceasedDateElement(deceasedEl.filter(':checked').val());
    });

    updateDeceasedDateElement(deceasedEl.filter(':checked').val());

    var updateDisabledElement = function (state) {
        if (state) {
            $("#personnel_form_disabilityReason_div").show();
            $("#personnel_form_disabilityReason").attr('required', 'required');
        } else {
            $("#personnel_form_disabilityReason_div").hide();
            $("#personnel_form_disabilityReason").removeAttr('required');
        }
    };
    disabledEl.on('switchChange.bootstrapSwitch', function (event, state) {
        updateDisabledElement(state);
    });

    updateDisabledElement(disabledEl.prop('checked'));

    var refreshDisabilityReason = function (value) {
        if (value === 'Medical Ground') {
            $("#disabilityReason").show();
        } else {
            $("#disabilityReason").hide();
        }
    };

    if(retirementReasonEl.length !== 0) {
        retirementReasonEl.on('change', function () {
            refreshDisabilityReason($(this).val());
        }).change();
    }

    if (!upazilaOpt.val()) {
        districtEl.change();
    }

    if (!upazilaSearch.val()) {
        districtSearch.change();
    }

    if (!permanentUpazilaOpt.val()) {
        permanentUpazilaOpt.change();
    }

    var inheritDistrictEl = $('#personnel_form_additionalInfo_inheritDistrict');
    var inheritUpazilaOpt = $('#personnel_form_additionalInfo_inheritUpazila');
    var inheritPermanentUpazilaOpt = $('#personnel_form_additionalInfo_inheritPermanentUpazila');

    $('.inheritDistrict').on('change', function () {
        var $el = $(this);
        var districtId = $el.val();
        var currentEl = null;

        switch ($el.data('upazila')) {
            case 'inheritPresent' :
                currentEl = inheritUpazilaOpt;
                break;
            case 'inheritPermanent' :
                currentEl = inheritPermanentUpazilaOpt;
                break;
            default:
                currentEl = null;
        }

        if (!districtId) {
            currentEl.html('<option value="">উপজেলা</option>');
            return;
        }

        $.get(Routing.generate('personnel_upazila_lookup', {district: districtId}), function (res) {
            var text = '<option value="">উপজেলা</option>';
            for (var i = 0; i < res.length; i++) {
                text += '<option value="' + res[i].id + '">' + res[i].name + '</option>';
            }

            if (currentEl) {
                currentEl.html(text);
            }

        })
    });

    if (!inheritUpazilaOpt.val()) {
        inheritDistrictEl.change();
    }

    if (!inheritPermanentUpazilaOpt.val()) {
        inheritPermanentUpazilaOpt.change();
    }

})(window.jQuery);