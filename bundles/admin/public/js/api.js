/**
 * Magic Rainbow Adventure Admin Interface
 *
 * API Client
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */

MagicRainbowAdmin.API = function() {

    var baseURL = '/api';

    function makeCall(type, method, callback) {
        callback = callback || function(){};

        $.ajax({
            type: type,
            url: baseURL + '/' + method,
            dataType: 'json',
            success: callback
        });
    };

    return {
        get: function(method, callback) {
            makeCall('GET', method, callback);
        },

        post: function(method, callback) {
            makeCall('POST', method, callback);
        },

        delete: function(method, callback) {
            makeCall('DELETE', method, callback);
        }
    };

}();
