/* Navegación común para todos los Owl del theme, incluso bloques cacheados. */
(function ($) {
    'use strict';
    var original = $.fn.owlCarousel;
    if (!original) return;
    function carousel(option) {
        if (typeof option !== 'string') {
            option = $.extend({}, option);
            var responsive = $.extend(true, {}, option.responsive || {});
            var desktop = {};
            var closest = -1;
            // Owl no acumula breakpoints: preservar la configuración desde 600px.
            $.each(responsive, function (width, settings) {
                if (+width <= 600 && +width > closest) {
                    closest = +width;
                    desktop = $.extend({}, settings);
                }
            });
            if (!Object.prototype.hasOwnProperty.call(responsive, 600)) {
                responsive[600] = desktop;
            }
            responsive[0] = $.extend({}, responsive[0]);
            $.each(responsive, function (width, settings) {
                if (+width < 600) {
                    settings.nav = false;
                    settings.dots = true;
                }
            });
            option.responsive = responsive;
        }
        var args = Array.prototype.slice.call(arguments);
        args[0] = option;
        return original.apply(this, args);
    }
    $.extend(carousel, original);
    $.fn.owlCarousel = carousel;
})(jQuery);