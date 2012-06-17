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
        var edit_container = $('.entry-info');

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

        // Open the edit modal
        self.edit = function() {
            ko.applyBindings(self, edit_container[0]);
            edit_container.modal('show');
        }

        // Save the entry via the API
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

    ko.applyBindings(new EntryViewModel(), $('.entries')[0]);

    $('.entry-info').modal({
        show: false,
        keyboard: false
    });

}();
