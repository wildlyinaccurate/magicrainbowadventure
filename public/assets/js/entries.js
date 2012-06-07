var MRA_Entries = function() {

    $('.entries').on('click', '.guest .favourite', function(event) {
        $(this).popover({
            content: $('.login-required').html(),
            trigger: 'manual'
        }).popover('show');

        event.preventDefault();
    });

    $('body:not(.guest) .entries').on('click', '.favourite', function(event) {
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
                        delay: {
                            show: 0,
                            hide: 3000
                        }
                    })
                    .popover('show')
                    .text(data.favourites_count)
                    .toggleClass('active', data.favourite);
            }
        });

        event.preventDefault();
    });

}();
