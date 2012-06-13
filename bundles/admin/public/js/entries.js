var Entry = Backbone.Model.extend({});

var EntryCollection = Backbone.Collection.extend({
	model: Entry,
	url: '/api/entries'
});
