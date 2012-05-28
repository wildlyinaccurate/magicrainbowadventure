var MRA_Entries = function() {

    var guest_buttons = $('.guest .favourite');

    guest_buttons.popover({
        content: $('.login-required').html(),
        trigger: 'manual'
    });

    guest_buttons.click(function(event) {
        $(this).popover('show');
        event.preventDefault();
    });

    var favourite_buttons = $(':not(.guest) .favourite');

    favourite_buttons.click(function(event) {
        $.ajax({
            url: $(this).prop('href'),
            type: 'POST',
            data: {
                favourite: ($(this).hasClass('active')) ? 0 : 1
            }
        });

        event.preventDefault();
    });

}();
