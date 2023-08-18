$(document).ready(function () {

    // we have to init each field separately, because it throws maximum call stack err
    // if we init all the datepickers with single selector
    function initDatePickers() {
        $.each($('.datepicker'), function(){
            $(this).datepicker({
                format: 'mm/dd/yyyy',
                "autoclose": true
            });
        });
    }
    initDatePickers();

    function reportCalc() {
        $.ajax({
            url: '/admin/report-data',
            method: 'get',
            dataType: 'json',
            data: {
                effective_date_from: $('.effective_date_from').val(),
                effective_date_to: $('.effective_date_to').val(),
                expiration_date_from: $('.expiration_date_from').val(),
                expiration_date_to: $('.expiration_date_to').val(),
                company: $('.company option:selected').val(),
                referral: $('.referral option:selected').val(),
                is_endorsement: $('.reports_is_endorsment').is(':checked') ? 1 : 0,
                policy_type: $('.res-policy-type-dropdown').val(),
                license_checkbox: $('.res-license-checkbox').is(':checked') ? 1 : 0,
                auto_club_checkbox: $('.res-auto-club-checkbox').is(':checked') ? 1 : 0,
            },
            success: function(res) {
                $('.res-policies').html(
                    res.data.policies_number || '-'
                );
                $('.res-premium').html(
                    res.data.premium_sum ? '$' + parseFloat(res.data.premium_sum).toFixed(2) : '-'
                );
                $('.res-down-payment').html(
                    res.data.down_payment_sum ? '$' + parseFloat(res.data.down_payment_sum).toFixed(2) : '-'
                );
                $('.res-ship-fee').html(
                    res.data.ship_fee_sum ? '$' + parseFloat(res.data.ship_fee_sum).toFixed(2) : '-'
                );
                $('.res-broker-fee').html(
                    res.data.down_payment_sum ? '$' + parseFloat(res.data.broker_fee_sum).toFixed(2) : '-'
                );
                $('.res-referral-fee').html(
                    res.data.down_payment_sum ? '$' + parseFloat(res.data.amount_sum).toFixed(2) : '-'
                );
                var tableBody = '';
                $.each(res.tableData, function(idx, item){
                    tableBody += '<tr>';
                        var effectiveDate = item.effective_date || '-';
                        if (item.ac_effective_date) {
                            effectiveDate += '<br />' + item.ac_effective_date || '-';
                        }
                        if (item.l_effective_date) {
                            effectiveDate += '<br />' + item.l_effective_date || '-';
                        }
                        tableBody += '<td>' + effectiveDate + '</td>';

                        var expirationDate = item.expiration_date || '-';
                        if (item.ac_expiration_date) {
                            expirationDate += '<br />' + item.ac_expiration_date || '-';
                        }
                        if (item.l_expiration_date) {
                            expirationDate += '<br />' + item.l_expiration_date || '-';
                        }

                        tableBody += '<td>' + expirationDate + '</td>';
                        tableBody += '<td>' + item.number + '</td>';
                        tableBody += '<td>' + item.first_name + ' ' + item.last_name + '</td>';

                        var policyNumber = item.policy_number || '-';
                        if (item.ac_policy_number) {
                            policyNumber += '<br />' + item.ac_policy_number || '-';
                        }
                        if (item.l_policy_number) {
                            policyNumber += '<br />' + item.l_policy_number || '-';
                        }
                        tableBody += '<td>' + policyNumber + '</td>';

                        var premium =  parseFloat(item.premium || 0).toFixed(2);
                        if (item.ac_premium) {
                            premium += '<br />' + parseFloat(item.ac_premium || 0).toFixed(2);
                        }
                        if (item.l_premium) {
                            premium += '<br />' + parseFloat(item.l_premium || 0).toFixed(2);
                        }
                        tableBody += '<td>' + premium + '</td>';

                        var downPayment =  parseFloat(item.company_down_payment || 0).toFixed(2);
                        if (item.ac_down_payment) {
                            downPayment += '<br />' + parseFloat(item.ac_down_payment || 0).toFixed(2);
                        }
                        if (item.l_down_payment) {
                            downPayment += '<br />' + parseFloat(item.l_down_payment || 0).toFixed(2);
                        }
                        tableBody += '<td>' + downPayment + '</td>';

                        var brokerFee =  parseFloat(item.broker_fee || 0).toFixed(2);
                        if (item.ac_broker_fee) {
                            brokerFee += '<br />' + parseFloat(item.ac_broker_fee || 0).toFixed(2);
                        }
                        if (item.l_broker_fee) {
                            brokerFee += '<br />' + parseFloat(item.l_broker_fee || 0).toFixed(2);
                        }
                        tableBody += '<td>' + brokerFee + '</td>';

                        var amount =  parseFloat(item.amount || 0).toFixed(2);
                        if (item.ac_referral_fee) {
                            amount += '<br />' + parseFloat(item.ac_referral_fee || 0).toFixed(2);
                        }
                        if (item.l_amount) {
                            amount += '<br />' + 0;
                        }
                        tableBody += '<td>' + amount + '</td>';
                    tableBody += '</tr>';
                });
                $('.report-table tbody').html(tableBody);
            }
        });
    }
    reportCalc();

    $('input, select').on('change', function(){
        if ($(this).hasClass('res-policy-type-dropdown')) {
            var policyType = $('.res-policy-type-dropdown option:selected').text().trim();
            $('.auto-club-box, .license-box').hide();
            $('.auto-club-checkboxes input[type="checkbox"]').prop('checked', false);

            if (policyType == 'Personal') {
                $('.auto-club-box, .license-box').show();
            } else if (policyType == 'Auto Club') {
                $('.license-box').show();
            }
        }
        reportCalc();
    });

    $('.print-btn').on('click', function(){
        window.print();
    });
});
