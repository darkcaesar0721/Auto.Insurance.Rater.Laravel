 $(document).ready(function () {
    $('#second-cell-phone').hide();
    $('#international-box').hide();
    $('#add-cell-phone').on('click', function(){
        $('#second-cell-phone').show();
    });

    if ($('.second-cell-phone').length != 0) {
        $('#second-cell-phone').show();
    };

    $('#client-type').on('change', function(){
        var clientTypeValue = $("#client-type option:selected").text();

        if (clientTypeValue == "Prospect") {
            $('#nav-policies-tab').hide();
            $('#nav-attachment-tab').hide();
        } else {
            $('#nav-policies-tab').show();
            $('#nav-attachment-tab').show();
        }
    });
    $('#client-type').trigger('change');
    // $('#client-type').change();

    $(document).on('change', '.payment-method-dropdown', function(){
        var $policy = $(this).parents('.policy-row');
        var paymentMethod = $policy.find(".payment-method-dropdown option:selected").text();
        if (paymentMethod == 'Referral-Customer Pay') {
            $policy.find('.check-no-field').show();
        } else {
            $policy.find('.check-no-field').hide();
        }

        if ($policy.find('.license-term').length) {
            $policy.find('.license-term').trigger('change');
        }
    });
    $('.payment-method-dropdown').trigger('change');
    // $('.payment-method-dropdown').change();

    $(document).on('change', '.payment-method-dropdown', function(){
        var $policy = $(this).parents('.policy-row');
        var paymentMethod = $policy.find(".payment-method-dropdown option:selected").text();
        if (paymentMethod  == "Referral-Customer Pay" || paymentMethod  == "Referral Pay") {
            $policy.find('.referral-source-fields').show();
            $policy.find('.referral-fee-fields').show();
            $policy.find('.referral-fee-container').show();
        } else {
            $policy.find('.referral-source-fields').hide();
            $policy.find('.referral-fee-fields').hide();
            $policy.find('.referral-fee-container').hide();

        }
    });
    $('.payment-method-dropdown').change();

    $(document).on('change', '.payment-method-dropdown-license', function(){
        var $policy = $(this).parents('.policy-row');
        var paymentMethod = $policy.find(".payment-method-dropdown-license option:selected").text();
        if (paymentMethod == 'Referral-Customer Pay') {
            $policy.find('.check-no-field-license').show();
        } else {
            $policy.find('.check-no-field-license').hide();
        }
    });
    

    $(document).on('change', '.payment-method-dropdown-license', function(){
        var $policy = $(this).parents('.policy-row');
        var paymentMethod = $policy.find(".payment-method-dropdown-license option:selected").text();
        if (paymentMethod  == "Referral-Customer Pay" || paymentMethod  == "Referral Pay") {
            $policy.find('.referral-source-fields-license').show();
            $policy.find('.referral-fee-fields-license').show();
            $policy.find('.referral-fee-container-license').show();
        } else {
            $policy.find('.referral-source-fields-license').hide();
            $policy.find('.referral-fee-fields-license').hide();
            $policy.find('.referral-fee-container-license').hide();
        }
    });
    $('.payment-method-dropdown-license').trigger('change');
    // $('.payment-method-dropdown-license').change();

    $(document).on('change', '.policy-type-dropdown', function(){
        var $main = $(this).parents('.main-info-client');
        var policyType = $main.find(".policy-type-dropdown option:selected").text();
        var licenseCheckbox = $("#license-checkbox").is(':checked');
        var autoClubCheckbox = $("#auto-club-checkbox").is(':checked');
        var interCheckbox = $("#international-checkbox").is(':checked');

        if(policyType == "License"){
            $("#auto-club-checkbox").attr('checked', false);
            $("#license-checkbox").attr('checked', false);

            $main.find('.client-characteristics').show();
            $main.find('.client-characteristics-container').show();
            $main.find('#international-box').show();
            if (interCheckbox) {
                $('#current_address_address_state_id').attr('disabled',true);
            }
        } else if(licenseCheckbox && (policyType == "Auto Club" || policyType == "Personal")){
            $main.find('#international-box').show();
            $main.find('.client-characteristics').show();
            $main.find('.client-characteristics-container').show();
            if (interCheckbox) {
                $('#current_address_address_state_id').attr('disabled',true);
            }
        } else if(autoClubCheckbox && policyType == "Personal") {
            $main.find('.client-characteristics').show();
            $main.find('.client-characteristics-container').hide();
            $main.find('#international-box').hide();
            $('#current_address_address_state_id').attr('disabled',false);
        } else if(policyType == "Auto Club") {
            $main.find('.client-characteristics').show();
            $main.find('.client-characteristics-container').hide();
            $main.find('#international-box').hide();
            $('#current_address_address_state_id').attr('disabled',false);
            // licenseCheckbox = document.getElementById("license-checkbox").checked = false;
            $("#license-checkbox").attr('checked', false);
        } else {
            $("#auto-club-checkbox").attr('checked', false);
            $("#license-checkbox").attr('checked', false);
            $main.find('#international-box').hide();
            $('#current_address_address_state_id').attr('disabled',false);
            // autoClubCheckbox = document.getElementById("auto-club-checkbox").checked = false;
            // licenseCheckbox = document.getElementById("license-checkbox").checked = false;
            $main.find('.client-characteristics').hide();
            $main.find('.client-characteristics-container').hide();
        }
    });
    $('.policy-type-dropdown').trigger('change');
    // $('.policy-type-dropdown').change();
    
    $(document).on('change', '#international-checkbox', function(){
        var interCheckbox = $("#international-checkbox").is(':checked');
        if (interCheckbox) {
            $('#current_address_address_state_id').attr('disabled',true);
        }
        else {
            $('#current_address_address_state_id').attr('disabled',false);
        }
    });
    $('#international-checkbox').trigger('change');
    
    $(document).on('change', '.auto-club-checkboxes', function(){
        var policyType = $("#policy-type option:selected").text();
        var interCheckbox = $("#international-checkbox").is(':checked');
        var licenseCheckbox = $("#license-checkbox").is(':checked');
        var autoClubCheckbox = $("#auto-club-checkbox").is(':checked');
        if(policyType == "Auto Club" && !licenseCheckbox){
            $('.client-characteristics').show();
            $('.client-characteristics-container').hide();
            $('#international-box').hide();
            $('#current_address_address_state_id').attr('disabled',false);
        } else if(licenseCheckbox && (policyType == "Auto Club" || policyType == "Personal")){
            $('.client-characteristics').show();
            $('.client-characteristics-container').show();
            $('#international-box').show();
            if (interCheckbox) {
                $('#current_address_address_state_id').attr('disabled',true);
            }
        } else if(autoClubCheckbox && policyType == "Personal") {
            $('.client-characteristics').show();
            $('.client-characteristics-container').hide();
            if (licenseCheckbox) {
                $('#international-box').show();
                if (interCheckbox) {
                    $('#current_address_address_state_id').attr('disabled',true);
                }
            }
            else {
                $('#international-box').hide();
                $('#current_address_address_state_id').attr('disabled',false);
            }
        } else if(policyType == "License"){
            $('.client-characteristics').show();
            $('.client-characteristics-container').show();
            $('#international-box').show();
            if (interCheckbox) {
                $('#current_address_address_state_id').attr('disabled',true);
            }
        } else {
            $('.client-characteristics').hide();
            $('.client-characteristics-container').hide();
            $('#international-box').hide();
            $('#current_address_address_state_id').attr('disabled',false);
        }   
    });
    $('.auto-club-checkbox').trigger('change');

    // $('.auto-club-checkboxes').change();

    $('.agent-no-field').mask('000-000-0000');
    $('.phone-no').mask('000-000-0000');
    $('.client-fax-number').mask('000-000-0000');
    $('.client-number input').mask('000000');
    $('.zip-code-field').mask('00000');

    $('.add-home-phone').on('click', function(){
        var copyCellPhone = $('.cell-phone').val();
        $(".copy-phone").val(copyCellPhone);
    });

    function noEmail() {
        if ($('.no-email').is(':checked')) {
            $('.email-address').val('').attr('disabled', true);
            var phoneVal = $('.contact-method option:contains("Phone")').val();
            $('.contact-method').val(phoneVal).attr('readonly', true);
        } else {
            $('.email-address').attr('disabled', false);
            $('.contact-method').attr('readonly', false);
        }
    }
    $('.no-email').on('change', function(){
        noEmail();
    });
    noEmail();

    $('.mailing-address-checkbox').on('change', function(){
        $('.mailing-address').addClass('d-none');
        if ($(this).is(':checked')) {
            $('.mailing-address').removeClass('d-none');
        }
    });

    $('#policy-type').on('change', function(){
        var fieldValue = $("#policy-type option:selected").text();

        if (fieldValue == "Commercial") {
            $('.client-name-text').hide();
            $('.contact-person-text').show();
            $('.business-name-block').show();
            $('.fax-number').show();
            $('.auto-club-box, .license-box').hide();
        } else {
            if (fieldValue == 'Auto Club') {
                $('.auto-club-box').hide();
                $('.license-box').show();
                // $('.client-characteristics-container').show();
                // $('.client-characteristics').show();
            } else if (fieldValue == 'License'){
                $('.auto-club-box, .license-box').hide();
                $('.client-characteristics').show();
                $('.client-characteristics-container').show();
            } else {
                $('.auto-club-box, .license-box').show();
            }

            $('.business-name-block').hide();
            $('.client-name-text').show();
            $('.contact-person-text').hide();
            $('.fax-number').hide();
        }
    });
    $('#policy-type').trigger('change'); 

    $('#client-type').on('change', function(){
        var fieldValue = $("#client-type option:selected").text();

        if (fieldValue == 'Client') {
            $('.client-number').show();
        } else {
            $('.client-number').hide();
        }
    });
    $('#client-type').trigger('change');

    // we have to init each field separately, because it throws maximum call stack err
    // if we init all the datepickers with single selector
    function initDatePickers() {
        $.each($('.datepicker'), function(){
            $(this).datepicker({
                format: 'mm/dd/yyyy',
                "autoclose": true
            });

            $('.effective_date').on('change', function(){
                var $policy = $(this).parents('.policy-row');
                var effectiveDate = $policy.find('.effective_date').val();
                $policy.find(".expiration_date").val(effectiveDate);
                $policy.find('.term').change();
            });

        });
        $.each($('.datepicker-license'), function(){
            $(this).datepicker({
                format: 'mm/dd/yyyy',
                "autoclose": true
            });

            $('.effective_date_license').on('change', function(){
                var $policy = $(this).parents('.policy-row');
                var effectiveDate = $policy.find('.effective_date_license').val();
                $policy.find(".expiration_date_license").val(effectiveDate);
                $policy.find('.term').change();
            });

        });
    }
    initDatePickers();

    $(document).on('change', '.term', function(){
        var $policy = $(this).parents('.policy-row');
        var startDate = $policy.find(".effective_date").val();
        var numOfMonth = 0
        var expireDate = new Date(startDate);
        var term = $policy.find(".term option:selected").text();
        if (term == "Year") {
            numOfMonth = 12;
        }
        if (term == "6 Months") {
            numOfMonth = 6;
        }
        if (term == "3 Months") {
            numOfMonth = 3;
        }
        if (term == "Monthly") {
            numOfMonth = 1;
        }
        
        expireDate = expireDate.setMonth(expireDate.getMonth() + numOfMonth);
        var newDate = new Date(expireDate).toLocaleDateString("en-US");

        $policy.find(".expiration_date").val(moment(newDate, 'MM-DD-YYYY').format('MM/DD/YYYY'));
        
    });
    $('.term').trigger('change');
    
    $(document).on('change', '.license-term', function(){
        var $policy = $(this).parents('.policy-row');
        var startDate = $policy.find(".effective_date_license").val();
        var numOfMonth = 0
        var expireDate = new Date(startDate);
        var term = $policy.find(".license-term option:selected").text();

        var licensePrice = '0.00';
        var paymentMethod = $policy.find(".payment-method-dropdown option:selected").text();
        var isReferralPrice = paymentMethod == 'Referral Pay' || paymentMethod == 'Referral-Customer Pay';
        var shipFee = $("#ship-fee-license").val();
        if(!shipFee) {
            shipFee = 0.00;
        }

        if (term == "Year") {
            numOfMonth = 12;
            licensePrice = isReferralPrice ? '35.00' : '45.00';
        }
        if (term == "3 Years") {
            numOfMonth = 36;
            licensePrice = isReferralPrice ? '50.00' : '65.00';
        }
        if (term == "5 Years") {
            numOfMonth = 60;
            licensePrice = isReferralPrice ? '65.00' : '75.00';
        }
        if (term == "10 Years") {
            numOfMonth = 120;
            licensePrice = isReferralPrice ? '70.00' : '95.00';
        }

        $policy.find('.license-price').val(licensePrice);
        $("#total-cost-license").val(parseFloat(licensePrice) + parseFloat(shipFee));
        
        expireDate = expireDate.setMonth(expireDate.getMonth() + numOfMonth);
        var newDate = new Date(expireDate).toLocaleDateString("en-US");
        $policy.find(".expiration_date_license").val(moment(newDate, 'MM-DD-YYYY').format('MM/DD/YYYY'));
        
    });
    $('.license-term').trigger('change');

    $(document).on('change', '.auto-club-term', function(){
        var $wrapper = $(this).parents('.policy-row');
        var term = $wrapper.find(".auto-club-term option:selected").text();
        if (term == "Year") {
            $wrapper.find('.premium').val('249.95');
            $wrapper.find('.co_fees').val('0.00');
            $wrapper.find('.company_down_payment').val('249.95');
            $wrapper.find('.amount').val('50.00');
            $wrapper.find('.monthly_payment').val('249.95');
            $wrapper.find('.company-total').val('249.95');
        }
        if (term == "6 Months") {
            $wrapper.find('.premium').val('119.95');
            $wrapper.find('.co_fees').val('10.00');
            $wrapper.find('.company_down_payment').val('129.95');
            $wrapper.find('.amount').val('25.99');
            $wrapper.find('.monthly_payment').val('119.95');
            $wrapper.find('.company-total').val('129.95');
        }
        if (term == "3 Months") {
            $wrapper.find('.premium').val('59.95');
            $wrapper.find('.co_fees').val('15.00');
            $wrapper.find('.company_down_payment').val('74.95');
            $wrapper.find('.amount').val('14.99');
            $wrapper.find('.monthly_payment').val('59.95');
            $wrapper.find('.company-total').val('74.95');
        }
        if (term == "Monthly") {
            $wrapper.find('.premium').val('19.95');
            $wrapper.find('.co_fees').val('25.00');
            $wrapper.find('.company_down_payment').val('44.95');
            $wrapper.find('.amount').val('8.99');
            $wrapper.find('.monthly_payment').val('19.95');
            $wrapper.find('.company-total').val('44.95');
        }
    });
    $('.auto-club-term').trigger('change');

    $(document).on('change', '.policy-company-list', function(){
        var $policy = $(this).parents('.policy-row');
        var companyId = $(this).val();
        $policy.find('.policy-website-link').attr('href', '/admin/company/website/' + companyId);
    });
    $('.policy-company-list').trigger('change');

    $(document).on('change', '.referral-fee-dropdown', function(){
        var $policy = $(this).parents('.policy-row');

        var showReferralFee = $policy.find(".referral-fee-dropdown option:selected").text();
        if (showReferralFee == 'Yes') {
            $policy.find('.referral-fee-container').show();
        } else {
            $policy.find('.referral-fee-container').hide();
        }
    });
    $('.referral-fee-dropdown').trigger('change');

    $(document).on('input', '.premium, .co_fees, .broker_fee', function(){
        var $policy = $(this).parents('.policy-row');
        var premiumVal = $policy.find('.premium').val() || 0;
        var coFeesVal = $policy.find('.co_fees').val() || 0;
        var brokerFeeVal = $policy.find('.broker_fee').val() || 0;
        
        var sum = parseFloat(premiumVal) +
            parseFloat(coFeesVal) +
            parseFloat(brokerFeeVal);
        $policy.find('.agency-total').val(sum.toFixed(2));
    });

    $(document).on('input', '.premium, .co_fees', function(){
        var $policy = $(this).parents('.policy-row');
        var premiumVal = $policy.find('.premium').val() || 0;
        var coFeesVal = $policy.find('.co_fees').val() || 0;
        
        var sum = parseFloat(premiumVal) +
            parseFloat(coFeesVal);
        $policy.find('.company-total').val(sum.toFixed(2));
    });

    $(document).on('input', '.company_down_payment, .broker_fee', function(){
        var $policy = $(this).parents('.policy-row');
        var companyDownPaymentVal = $policy.find('.company_down_payment').val() || 0;
        var brokerFeeVal = $policy.find('.broker_fee').val() || 0;
        
        var sum = parseFloat(companyDownPaymentVal) +
            parseFloat(brokerFeeVal);
        $policy.find('.total-down-payment').val(sum.toFixed(2));
    });

    $('.btn-add-policy').on('click', function(){
        var policyIndex = $('.policy-wrapper .policy-row').length;
        $.ajax({
            url: '/admin/clients/add-policy',
            data: {
                policyIndex: policyIndex,
                clientId: $('#client_id').val()
            },
            dataType: 'json',
            success: function(res) {
                $('.policy-wrapper').append(res.policy);
                $('.referral-fee-dropdown').change();
                $('.policy-referral-source').change();
                initDatePickers();
                $('.term').change();
                $('.policy-company-list').change();
            }
        });
    });
    
    $(document).on('change', '.premium', function(){
        var $policy = $(this).parents('.policy-row');
        var premiumVal = $policy.find('.premium').val() || 0;
        var zero = 0;
        var sum = parseFloat(premiumVal) +
            parseFloat(zero);
        $policy.find('.premium').val(sum.toFixed(2));
    });
    $('.premium').trigger('change');

    $(document).on('change', '.co_fees', function(){
        var $policy = $(this).parents('.policy-row');
        var coFeesVal = $policy.find('.co_fees').val() || 0;
        var zero = 0;
        var sum = parseFloat(coFeesVal) +
            parseFloat(zero);
        $policy.find('.co_fees').val(sum.toFixed(2));
    });
    $('.co_fees').trigger('change');

    $(document).on('change', '.company_down_payment', function(){
        var $policy = $(this).parents('.policy-row');
        var companyDownPaymentVal = $policy.find('.company_down_payment').val() || 0;
        var zero = 0;
        var sum = parseFloat(companyDownPaymentVal) +
            parseFloat(zero);
        $policy.find('.company_down_payment').val(sum.toFixed(2));
    });
    $('.company_down_payment').trigger('change');

    $(document).on('change', '.monthly_payment', function(){
        var $policy = $(this).parents('.policy-row');
        var monthlyPaymentVal = $policy.find('.monthly_payment').val() || 0;
        var zero = 0;
        var sum = parseFloat(monthlyPaymentVal) +
            parseFloat(zero);
        $policy.find('.monthly_payment').val(sum.toFixed(2));
    });
    $('.monthly_payment').trigger('change');

    $(document).on('change', '.broker_fee', function(){
        var $policy = $(this).parents('.policy-row');
        var brokerFeeVal = $policy.find('.broker_fee').val() || 0;
        var zero = 0;
        var sum = parseFloat(brokerFeeVal) +
            parseFloat(zero);
        $policy.find('.broker_fee').val(sum.toFixed(2));
    });
    $('.broker_fee').trigger('change');

    $(document).on('change', '.amount', function(){
        var $policy = $(this).parents('.policy-row');
        var amountVal = $policy.find('.amount').val() || 0;
        var zero = 0;
        var sum = parseFloat(amountVal) +
            parseFloat(zero);
        $policy.find('.amount').val(sum.toFixed(2));
    });
    $('.amount').trigger('change');

    $(document).on('change', '.is-endorsement', function(){
        var $policy = $(this).parents('.policy-row');
        var endorsementField = $policy.find(".is-endorsement");
        var termField = $policy.find(".term-field");
        var expirationDateField = $policy.find(".expiration-date-field");

        if (endorsementField.is(":checked")) {
            termField.hide();
            expirationDateField.hide();
            $policy.find('.policy-title').html('Endorsement');
        } else {
            termField.show();
            expirationDateField.show();
            $policy.find('.policy-title').html('Policy');
        }
    });
    $('.is-endorsement').trigger('change');


    var somethingChanged = false;
    $('#main-info-tab :input').on('change', function() {
        somethingChanged = true;
    });

    $('#policy-tab :input').on('change', function() {
        somethingChanged = true;
    });

    $('#attachment-tab :input').on('change', function() {
        somethingChanged = true;
    });
    
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        if (somethingChanged) {
            if (confirm('Do you want to save data before leaving this page?')) {
                $('form:visible').submit();
                e.preventDefault();
                return;
            } else {
                somethingChanged = false;
            }
        }
    });

    $(document).on('change', '.license-price-values', function(){
        var price = $("#price-license").val();
        var shipFee = $("#ship-fee-license").val();
        if(!shipFee){
            shipFee = 0.00;
        }
        if (!price) {
            price = 0.00;
        }
        $("#total-cost-license").val(parseFloat(price) + parseFloat(shipFee));
    });
    $(".license-price-values").trigger("change");

});
