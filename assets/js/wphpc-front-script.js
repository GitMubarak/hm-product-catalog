/*
(function($) {

    // USE STRICT
    "use strict";

    selectDropdown();

    // Select menu 
    function selectDropdown() {
        if ($(".selectmenu").length) {
            $(".selectmenu").selectmenu();

            var changeSelectMenu = function(event, item) {
                $(this).trigger('change', item);
            };
            $(".selectmenu").selectmenu({ change: changeSelectMenu });
        };
    }

})(jQuery);
*/
jQuery(document).on('ready', function() {
    (function($) {

        // USE STRICT
        "use strict";
        var url = new URL(window.location.href);
        var params = '';
        selectDropdown();

        $('.selectmenu').on('chnage', function() {
            alert('Hello');
        });

        // Select menu 
        function selectDropdown() {
            if ($(".selectmenu").length) {
                $(".selectmenu").selectmenu();

                var changeSelectMenu = function(event, item) {
                    $(this).trigger('change', item);
                    if ($(this).val() === '') {
                        url.searchParams.set('orderby', 'default');
                    }
                    if ($(this).val() === 'date') {
                        url.searchParams.set('orderby', 'date');
                    }
                    if ($(this).val() === 'price-low') {
                        url.searchParams.set('orderby', 'price');
                    }
                    if ($(this).val() === 'price-high') {
                        url.searchParams.set('orderby', 'price-desc');
                    }
                    window.location.href = url.href;
                };
                $(".selectmenu").selectmenu({ change: changeSelectMenu });
            };
        }
    })(jQuery);
});