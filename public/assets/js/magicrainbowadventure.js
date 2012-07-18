/**
 * Magic Rainbow Adventure base class
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
var MagicRainbowAdventure = function() {

    var self = this;

    self.user = {};

    return {

        Models: {},

        setUser: function(id) {
            if (typeof MagicRainbowAdventure.API === 'undefined') {
                console.error('setUser cannot be called until MagicRainbowAdventure.API is loaded.');
                return;
            }

            MagicRainbowAdventure.API.get('users/' + id, function(data) {
                self.user = data;
            });
        },

        getUser: function() {
            return self.user;
        }

    };

}();

MagicRainbowAdventure.Queue = function() {

    var isInit = false;
    var queue = [];

    return {

        onInit: function(callback) {
            if (isInit) {
                callback();
            } else {
                queue.push(callback);
            }
        },

        init: function() {
            while (queue.length) {
                (queue.shift())();
            }

            isInit = true;
        }

    };

}();
