/**
 * Magic Rainbow Adventure Admin Interface
 *
 * User Model
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
$.extend(MagicRainbowAdmin.Models, {

    User: function(data) {
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
    },

    UserViewModel: function() {
        var self = this;

        self.users = ko.observableArray([]);
    }

});
