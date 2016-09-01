(function ($) {

    $.entwine('ss', function ($) {
        $('.locale-switcher li').entwine({
            onclick: function (e) {
                this.addClass('selected')
                    .siblings()
                    .removeClass('selected');

                var locale = this.data('locale'),
                    url = window.location.href;

                this.redirect(url, locale);
            },

            redirect: function (currentUrl, locale) {
                window.location = this.updateQueryStringParameter(currentUrl, 'l', locale);
            },

            updateQueryStringParameter: function(uri, key, value) {
                var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
                var separator = uri.indexOf('?') !== -1 ? "&" : "?";
                if (uri.match(re)) {
                    return uri.replace(re, '$1' + key + "=" + value + '$2');
                }
                else {
                    return uri + separator + key + "=" + value;
                }
            }
        });
    });

})(jQuery);