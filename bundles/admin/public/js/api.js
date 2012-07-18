/**
 * Magic Rainbow Adventure Admin Interface
 *
 * API Client
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
MagicRainbowAdventure.API = function() {

    var self = this;

    self.perPage = 10;
    self.page = 1;
    self.baseURL = '/api';

    function makeCall(type, method, data, callback) {
        data = data || {};

        if (self.perPage !== null && ! data.per_page) {
            data.per_page = self.perPage;
        }

        data.page = self.page;

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

        setPage: function(page) {
            self.page = page;
        },

        setPerPage: function(perPage) {
            self.perPage = perPage;
        }
    };

}();
