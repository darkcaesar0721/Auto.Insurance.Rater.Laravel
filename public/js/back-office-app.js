$('document').ready(function () {
    $('.quote-table-row').click(function (e) {
        if ($(e.target).hasClass('expand-icon')) {
            return;
        }

        window.location.href = '/admin/auto/' + $(this).data('hash-id');
    });

    $(document).on('click', '.client-table-row', function (e) {
        if ($(e.target).hasClass('expand-icon')) {
            return;
        }

        window.location.href = '/admin/clients/edit/' + $(this).data('client-id');
    })

    $('.company-table-row').click(function (e) {
        if ($(e.target).hasClass('expand-icon')) {
            return;
        }

        window.location.href = '/admin/company/edit/' + $(this).data('company-id');
    })

    $('.referral-table-row').click(function (e) {
        if ($(e.target).hasClass('expand-icon')) {
            return;
        }

        window.location.href = '/admin/referral/edit/' + $(this).data('referral-id');
    })
});