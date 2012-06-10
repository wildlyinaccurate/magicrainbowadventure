/**
 * Magic Rainbow Adventure Base Class
 *
 * @type {MagicRainbowAdventure}
 * @author Joseph Wynn <joseph@wildlyinaccurate.com>
 */
var MagicRainbowAdventure = function() {

    // Register global AJAX handlers
    $(document).ajaxError(function(event, request, settings) {
        console.log(event, request, settings);
    });

    // Return an extendable object
    return {
        Models: {}
    };

}();

// Shorthand alias
var $MRA = MagicRainbowAdventure;
