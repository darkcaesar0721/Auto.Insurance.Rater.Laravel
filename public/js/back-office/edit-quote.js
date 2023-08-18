$('document').ready(function () {
    $('#payment-type-select').change(function() {
        if ($(this).val() === 'agent-pay') {
            $('#agent-fields').removeClass('d-none');
        } else {
            $('#agent-fields').addClass('d-none');
        }
    });

    $('.dob-field').mask('00/00/0000');

    $('.marital-status-select').change(function (e) {
        let driverIndex = $(this).data('index');
        let $marriedFieldsBlock = $('.married-fields-block-' + driverIndex);

        if (e.target.value === 'single') {
            if (!$marriedFieldsBlock.hasClass('d-none')) {
                $marriedFieldsBlock.addClass('d-none');
                $('.spouse-is-driver-block').addClass('d-none');
            }

        } else {
            $marriedFieldsBlock.removeClass('d-none')
        }
    });

    $('.spouse-is-driver-select').change(function(e) {
        let driverIndex = $(this).data('index');
        let $spouseIsDriverBlock = $('.spouse-is-driver-block-' + driverIndex);

        if (e.target.value === 'true') {
            $spouseIsDriverBlock.removeClass('d-none');
        } else {
            if (!$spouseIsDriverBlock.hasClass('d-none')) {
                $spouseIsDriverBlock.addClass('d-none');
            }
        }
    });

    $('#note-textarea').on('blur', function (e) {
        let url = window.location.pathname.replace(/\/+$/, "").replace('edit', '') + '/note';

        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: $("meta[name='csrf-token']").attr('content'),
                note: e.target.value
            },
            dataType: 'JSON',
            success: function(resultData) { alert("Note Updated Successfully!") }
        });
    })
});