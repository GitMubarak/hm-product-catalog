(function(window, $) {

    // USE STRICT
    "use strict";
    var url = new URL(window.location.href);
    var params = '';
    selectDropdown();

    // Select menu 
    function selectDropdown() {
        if ($(".selectmenu").length) {
            $(".selectmenu").selectmenu();

            var changeSelectMenu = function(event, item) {
                $(this).trigger('change', item);
                if ($(this).val() === '') {
                    url.searchParams.set('sorting-by', 'default');
                }
                if ($(this).val() === 'date') {
                    url.searchParams.set('sorting-by', 'date');
                }
                if ($(this).val() === 'price-low') {
                    url.searchParams.set('sorting-by', 'price');
                }
                if ($(this).val() === 'price-high') {
                    url.searchParams.set('sorting-by', 'price-desc');
                }
                window.location.href = url.href;
            };
            $(".selectmenu").selectmenu({ change: changeSelectMenu });
        };
    }

    $(document).on('click', '.hmpc-widget-category-a-id', function(event) {
        event.preventDefault();
        var url = new URL($(this).attr("href"));
        var urlParam = $(this).data('category');
        url.searchParams.set('category', urlParam);
        window.location.href = url.href;
    });

})(window, jQuery);