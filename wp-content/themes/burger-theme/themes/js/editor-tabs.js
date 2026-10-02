(function (acf, $) {
    'use strict';

    if (!acf || !$) return;

    // SCF remonta el formulario del editor extendido sin inicializar sus tabs.
    // Esperar a que los campos existan (prioridad 60) y usar el estado nativo
    // de ACF para respetar preferencias, endpoints y condiciones de visibilidad.
    acf.addAction('remount', function ($form) {
        if (!$form.closest('.acf-block-form-modal').length) return;

        $form.find('.acf-tab-wrap').each(function () {
            var tabs = $(this).data('acf');
            if (tabs && typeof tabs.initializeTabs === 'function' && !tabs.get('initialized')) {
                tabs.initializeTabs();
            }
        });
    }, 60);
})(window.acf, window.jQuery);
