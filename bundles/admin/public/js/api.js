/**
 * Magic Rainbow Adventure Admin Interface
 *
 * API Client
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */

MagicRainbowAdmin.API = function() {

    var baseURL = '/api';

    function makeCall(type, method, data, callback) {
        $.ajax({
            type: type,
            data: data,
            url: baseURL + '/' + method,
            dataType: 'json',
            success: callback
        });
    };

    return {
        get: function(method, callback) {
            makeCall('GET', method, null, callback);
        },

        post: function(method, data, callback) {
            makeCall('POST', method, data, callback);
        },

        delete: function(method, callback) {
            makeCall('DELETE', method, null, callback);
        }
    };

}();
