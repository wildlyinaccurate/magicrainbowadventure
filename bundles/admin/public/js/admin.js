/**
 * Magic Rainbow Adventure Admin Interface
 *
 * Base Javascript
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */

var MagicRainbowAdmin = function() {

    _.mixin({
        capitalize : function(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    });

}();
