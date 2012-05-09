var entries_container = $('.entries').first();
var load_more = $('.load-more');

var lazyloader = entries_container.lazyload({
    src: '/',
    scrollThreshold: 0.6,
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
    }
});

load_more.click(function(event) {
    lazyloader.load();
    event.preventDefault();
});
