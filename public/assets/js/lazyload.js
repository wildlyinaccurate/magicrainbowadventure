var MRA_Lazyload = function() {

    var entries_container = $('.entries').first();
    var load_more = $('.load-more');
    var no_more_entries = $('.no-more-entries');

    var lazyloader = entries_container.lazyload({
        src: '/',
        scrollThreshold: 0.9,
        loadStart: function() {
            load_more.activity({
                align: 'right',
                segments: 10,
                width: 2,
                length: 3.5,
                space: 1.6,
                speed: 1.6,
                padding: (load_more.width() / 2) - 60
            });
        },
        loadComplete: function() {
            load_more.activity(false);
        },
        loadSuccess: function(data, textStatus, jqXHR) {
            var entries = $(data).find('.entry');

            if (entries.length === 0) {
                this.settings.noResults();
                return;
            }

            entries.appendTo(entries_container);
        },
        noResults: function() {
            // this.lazyloader.disable();
            // load_more.hide();
            // no_more_entries.css('display', 'block');

        }
    });

    load_more.click(function(event) {
        lazyloader.load();
        event.preventDefault();
    });

}();
