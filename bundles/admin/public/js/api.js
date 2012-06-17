/**
 * Magic Rainbow Adventure Admin Interface
 *
 * API Client
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */

MagicRainbowAdmin.API = function() {

    var self = this;

    self.baseURL = '/api';
    self.perPage = null;

    function makeCall(type, method, data, callback) {
        if (self.perPage !== null && ! data.per_page) {
            data.per_page = self.perPage;
        }

        $.ajax({
            type: type,
            data: data,
            url: self.baseURL + '/' + method,
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
        },

        setPerPage: function(perPage) {
            self.perPage = perPage;
        }
    };

}();
