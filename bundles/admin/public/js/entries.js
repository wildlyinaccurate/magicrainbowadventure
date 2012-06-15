var Entry = Backbone.Model.extend({});

var EntryCollection = Backbone.Collection.extend({
	model: Entry,
	url: '/api/entries'
});

var EntryView = Backbone.View.extend({
    template: $('.entry-template').html(),

    render: function() {
        this.el = _.template(this.template, this.model.toJSON());

        return this;
    }
});

var AppView = Backbone.View.extend({
    tagName: 'tbody',

    initialize: function() {
        this.entries = new EntryCollection();
        this.entries.bind('all', this.render, this);
        this.entries.fetch();
    },

    render: function() {
        this.entries.each(function(entry) {
            $(this.el).append(new EntryView({model: entry}).render().el);
        }, this);

        return this;
    }
})

var app = new AppView();
$('.entries').append(app.render().el);
