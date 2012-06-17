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

        self.status = ko.computed(function() {
            return (self.approved()) ? 'Approved' : 'Declined';
        });

        self.moderatorDisplayName = ko.computed(function() {
            return (self.moderated_by()) ? self.moderated_by().display_name : '';
        });

        self.toggleApproved = function() {
            self.approved( ! self.approved());
            self.moderated_by(MagicRainbowAdmin.getUser());
            self.save();
        }

        self.save = function() {
            MagicRainbowAdmin.API.post('entries/' + self.id(), { entry: ko.toJSON(self) });
        }
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
