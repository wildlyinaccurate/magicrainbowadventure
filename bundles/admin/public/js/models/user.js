/**
 * User Model
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
MagicRainbowAdventure.Models.User = function(data) {
    var self = this;
    var edit_container = $('.user-info');

    for (property in data) {
        self[property] = ko.observable(data[property]);
    }

    // Open the edit modal
    self.edit = function() {
        ko.applyBindings(self, edit_container[0]);
        edit_container.modal('show');
    }

    self.getDisplayName = function() {
        return self.display_name() || self.username();
    }
};

/**
 * User View Model
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
MagicRainbowAdventure.Models.UserViewModel = function() {
        var self = this;

        self.users = ko.observableArray([]);
};
