var MRA_Entries = function() {

    var login_required = $('.guest .comments, .guest .favourite');

    login_required.popover({
        content: $('.login-required').text()
    });

    login_required.click(function(event) {
        login_required.popover('show');

        event.preventDefault();
    });

}();
