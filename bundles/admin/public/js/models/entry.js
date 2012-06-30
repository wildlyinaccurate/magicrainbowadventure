/**
 * Magic Rainbow Adventure Admin Interface
 *
 * Entry Model
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
$.extend(MagicRainbowAdmin.Models, {

    Entry: function(data) {
        var self = this;
        var edit_container = $('.entry-info');

        data.user = new MagicRainbowAdmin.Models.User(data.user);

        if (data.moderated_by !== null) {
            data.moderated_by = new MagicRainbowAdmin.Models.User(data.moderated_by);
        }

        for (property in data) {
            self[property] = ko.observable(data[property]);
        }

        self.status = ko.computed(function() {
            return (self.approved()) ? 'Approved' : 'Declined';
        });

        self.moderatorDisplayName = ko.computed(function() {
            return (self.moderated_by() !== null) ? self.moderated_by().display_name() : '';
        });

        self.toggleApproved = function() {
            self.approved( ! self.approved());
            self.moderated_by = new MagicRainbowAdmin.Models.User(MagicRainbowAdmin.getUser());
            self.save();
        }

        // Open the edit modal
        self.edit = function() {
            ko.cleanNode(edit_container[0]);
            ko.applyBindings(self, edit_container[0]);
            edit_container.modal('show');
        }

        // Save the entry via the API
        self.save = function() {
            MagicRainbowAdmin.API.post('entries/' + self.id(), { entry: ko.toJSON(self) });
        }
    },

    EntryViewModel: function() {
        var self = this;

        self.entries = ko.observableArray([]);

        MagicRainbowAdmin.API.get('entries', function(data) {
            var mappedEntries = $.map(data, function(entry_data) {
                return new MagicRainbowAdmin.Models.Entry(entry_data);
            });

            self.entries(mappedEntries);
        });
    }

});
