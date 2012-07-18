/**
 * Entry Model
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
MagicRainbowAdventure.Models.Entry = function(data) {
    var self = this;
    var edit_container = $('.entry-info');
    var edit_clone;

    data.user = new MagicRainbowAdventure.Models.User(data.user);

    if (data.moderated_by !== null) {
        data.moderated_by = new MagicRainbowAdventure.Models.User(data.moderated_by);
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

    self.originalSize = ko.computed(function() {
        return self.image_width() + ' x ' + self.image_height();
    });

    self.setApproved = function(approved) {
        self.approved(approved);
        self.moderated_by = new MagicRainbowAdventure.Models.User(MagicRainbowAdventure.getUser());
    };

    self.setApprovedAndSave = function(approved) {
        self.setApproved(approved);
        self.save();
    };

    // Open the edit modal
    self.edit = function() {
        edit_clone = new MagicRainbowAdventure.Models.Entry(ko.toJS(self));

        ko.cleanNode(edit_container[0]);
        ko.applyBindings(edit_clone, edit_container[0]);

        edit_container.modal('show');
    };

    // Save the entry via the API
    self.save = function() {
        MagicRainbowAdventure.API.post('entries/' + self.id(), { entry: ko.toJSON(self) });
    };
};

/**
 * Entry View Model
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
MagicRainbowAdventure.Models.EntryViewModel = function() {
    var self = this;

    self.entries = ko.observableArray([]);

    MagicRainbowAdventure.API.get('entries', function(data) {
        var mappedEntries = $.map(data, function(entry_data) {
            return new MagicRainbowAdventure.Models.Entry(entry_data);
        });

        self.entries(mappedEntries);
    });
};
