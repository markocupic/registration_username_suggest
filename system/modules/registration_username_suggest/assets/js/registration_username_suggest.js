(function ($) {
    $().ready(function () {

        $('.mod_registration input[name="username"]').after('<div id="username-errormsg-and-suggestions" class="username-errormsg-and-suggestions" style="display:none" role="alert"></div>');

        $('.mod_registration input[name="username"]').on('blur', function (event) {

            event.stopPropagation();
            var elInput = $(this);
            var errBox = $(this).closest('.mod_registration').find('.username-errormsg-and-suggestions');
            var username = elInput.val();
            var firstame = elInput.closest('.mod_registration').find('input[name="firstname"]').val();
            var lastname = elInput.closest('.mod_registration').find('input[name="lastname"]').val();

            $.ajax({
                    url: window.location.href,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        'isAjax': 'true',
                        'registration_username_suggest': 'true',
                        'username': username,
                        'firstname': firstame,
                        'lastname': lastname
                    }
                })
                .done(function (response) {

                    if (response.status == 'valid') {
                        errBox.slideUp();
                        return true;
                    }
                    if (response.status == 'invalid') {
                        var arrUsern = [];
                        $.each(response.usernames, function (index, value) {
                            arrUsern.push('<a class="valid-user-name">' + value + '</a>');
                        });
                        var txt1 = response.messageLine1 + '<br><br>';
                        var txt2 = response.messageLine2 != '' ? response.messageLine2 + ': ' + arrUsern.join(', ') : '';
                        errBox.html(txt1 + txt2);
                        errBox.slideDown();

                        errBox.find('.valid-user-name').click(function (e) {
                            e.stopPropagation();
                            elInput.val($(this).text());
                            elInput.prop('value', $(this).text());
                            errBox.slideUp();
                        });
                    }
                })
                .fail(function () {
                    //
                })
                .always(function (response) {
                });
        });

        /**
         * Sanitize input
         */
        $('.mod_registration input[name="username"]').on('keyup', function (event) {
            $(this).val(sanitizeString($(this).val()));
        });

        /**
         * Close error-msg on focus
         */
        $('.mod_registration input[name="username"]').on('focus', function (event) {
            $(this).closest('.mod_registration').find('.username-errormsg-and-suggestions').slideUp();
        });


        /**
         *
         * @param v
         * @returns {string|*}
         */
        function sanitizeString(v) {
            v = v.toLowerCase();
            v = v.replace("ä", "ae").replace("ü", "ue").replace("ö", "oe");
            v = v.replace("è", "e").replace("é", "e").replace("à", "a").replace("ç", "c");
            return v;
        }

    });
})(jQuery);