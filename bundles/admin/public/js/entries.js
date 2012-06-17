/**
 * Magic Rainbow Adventure Admin Interface
 *
 * Entry Management
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
MagicRainbowAdmin.Entries = function() {

    function Entry(data) {
        var self = this;

        for (property in data) {
            self[property] = ko.observable(data[property]);
        }

        self.statusText = ko.computed(function() {
            if (self.moderated_by()) {
                var verb = (self.approved()) ? 'Approved' : 'Declined';
                return verb + ' by ' + self.moderated_by().display_name;
            }

            return 'Awaiting Moderation';
        });
    }

    function EntryViewModel() {
        var self = this;

        self.entries = ko.observableArray([]);

        MagicRainbowAdmin.API.get('entries', function(data) {
            var mappedEntries = $.map(data, function(entry_data) {
                return new Entry(entry_data);
            });

            self.entries(mappedEntries);
        });
    }

    ko.applyBindings(new EntryViewModel());

}();
