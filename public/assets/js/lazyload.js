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
                padding: 300
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
            this.lazyloader.disable();
            load_more.hide();
            no_more_entries.css('display', 'block');

        }
    });

    load_more.click(function(event) {
        lazyloader.load();
        event.preventDefault();
    });

    var clicker = function() {

        this.click_count = 0;
        this.messages = {
            10: "Seriously, there isn't anything else to load!",
            20: "Okay, keep clicking. Maybe something will happen eventually...",
            50: "That was a joke. Nothing is going to happen if you keep clicking.",
            100: "Well, this has been fun but... I have to go now."
        };

    };

    no_more_entries_clicker = new clicker();

    no_more_entries.click(function(event) {
        no_more_entries_clicker.click_count++;

        if (no_more_entries_clicker.click_count in no_more_entries_clicker.messages) {
            $(this).text(no_more_entries_clicker.messages[no_more_entries_clicker.click_count]);
        }

        event.preventDefault();
    });

}();
