/**
 * Magic Rainbow Adventure Admin Interface
 *
 * Entry Management
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
MagicRainbowAdventure.Admin.Entries = function() {

    // Set the current page
    var active_page = $('.pagination .active a');

    if (active_page.length > 0) {
        MagicRainbowAdventure.API.setPage(parseInt(active_page[0].innerHTML));
    }

    var view_model = new MagicRainbowAdventure.Models.EntryViewModel();

    ko.applyBindings(view_model, $('.entries')[0]);

    $('.entry-info, .user-info').modal({
        show: false,
        keyboard: false
    });

    return {
        getViewModel: function() {
            return view_model;
        }
    };

}();
