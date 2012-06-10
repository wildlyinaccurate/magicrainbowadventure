var MRA_Entries = function() {

    var close_popover = function(element) {
        setTimeout(function() {
            element.popover('hide');
        }, 5000);
    };

    $('body.guest').on('click', '.entry .favourite', function(event) {
        $(this).popover({
            content: $('.login-required').html(),
            trigger: 'manual'
        }).popover('show');

        close_popover($(this));

        event.preventDefault();
    });

    $('body:not(.guest)').on('click', '.entry .favourite', function(event) {
        var button = $(this);

        $.ajax({
            url: button.prop('href'),
            type: 'POST',
            data: {
                favourite: (button.hasClass('active')) ? 0 : 1
            },
            success: function(data, textStatus, jqXHR) {
                button.attr('data-content', data.message)
                    .popover({
                        trigger: 'manual'
                    })
                    .popover('show')
                    .text(data.favourites_count)
                    .toggleClass('active', data.favourite);

                    close_popover(button);
            }
        });

        event.preventDefault();
    });

}();
