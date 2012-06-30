/**
 * Magic Rainbow Adventure Admin Interface
 *
 * Base Javascript
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */

var MagicRainbowAdmin = function() {

	var self = this;

	self.user = {};

    return {

        Models: {},

    	setUser: function(id) {
    		MagicRainbowAdmin.API.get('users/' + id, function(data) {
    			self.user = data;
    		});
    	},

    	getUser: function() {
    		return self.user;
    	}

    };

}();
