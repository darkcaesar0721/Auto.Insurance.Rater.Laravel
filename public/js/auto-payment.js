$('document').ready(function() {
    $('.card_no').mask('0000-0000-0000-0000');
    $('.expiry_date').mask('00/2000');
    $('.phone_us').mask('(000) 000-0000');
    $('.agent_no').mask('000-000-0000');

    $('#submit_btn').click(function() {
        $('#payment_form').submit();
    });

    $('.payment-type-radio').change(function (e) {
        let val = e.target.value;
        let $ccInfo = $('.cc-info');
        let $agentInfo = $('.agent-info');

        if (val === 'agent-pay') {
            $ccInfo.fadeOut(function() {
                $agentInfo.fadeIn();
            });
        } else {
            $agentInfo.fadeOut(function() {
                $ccInfo.fadeIn();
            });
        }
    })
});