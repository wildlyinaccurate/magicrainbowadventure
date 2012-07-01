/**
 * Magic Rainbow Adventure Admin Interface
 *
 * Entry Management
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
MagicRainbowAdmin.Entries = function() {

	// Set the current page
	var active_page = $('.pagination .active a');

	if (active_page.length > 0) {
		MagicRainbowAdmin.API.setPage(parseInt(active_page[0].innerHTML));
	}

    ko.applyBindings(new MagicRainbowAdmin.Models.EntryViewModel(), $('.entries')[0]);

    $('.entry-info, .user-info').modal({
        show: false,
        keyboard: false
    });

}();
