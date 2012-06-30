/**
 * Magic Rainbow Adventure Admin Interface
 *
 * Entry Management
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
MagicRainbowAdmin.Entries = function() {

    ko.applyBindings(new MagicRainbowAdmin.Models.EntryViewModel(), $('.entries')[0]);

    $('.entry-info, .user-info').modal({
        show: false,
        keyboard: false
    });

}();
