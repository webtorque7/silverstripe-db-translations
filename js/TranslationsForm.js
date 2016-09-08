(function ($) {
    $.entwine('ss', function ($) {
        $('#TranslatablePhraseForm_TranslatablePhraseForm_action_updateTranslatable').entwine({
            onclick: function (e) {
                e.preventDefault();
                var form = this.closest('form');

                form.addClass('loading');
                this.closest('form').ajaxSubmit({
                    success: function (response) {
                        form.removeClass('loading');
                    }
                });
            }
        });

        // fix for silverstripe loading overlay  not covering overflow-y
        $('.cms-content-loading-overlay').entwine({
            onmatch: function(){
                $('.cms-content-loading-overlay').css("position", "fixed");
            }
        });
    });
})(jQuery);