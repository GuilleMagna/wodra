jQuery(document).ready(function($) {

    $('form.wpcf7-form.invalid').find('.wpcf7-validates-as-required').attr('aria-invalid', 'true');

    const html = '<button type="submit" class="btn btn-secundario px-3">' +
                    'ENVIAR CONSULTA' +
                    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
                        '<path d="M11 8.5L14.5 12L11 15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>' +
                        '<path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>' +
                    '</svg>' +
                '</button>';

    $('.wpcf7-submit').replaceWith(html);

    // Newsletters (802 y 1033): se quedan en la página, sin ir a /gracias/. blitz. 23/10/2026
    const formulariosSinGracias = ['802', '1033'];

    function paginaDeGracias(form) {
        var bloque = form.closest('[data-burger-thanks-url]');
        var destino = bloque && bloque.getAttribute('data-burger-thanks-url');
        try {
            var url = new URL(destino || '/gracias/', window.location.href);
            if (url.origin === window.location.origin && /^https?:$/.test(url.protocol)) {
                return url.href;
            }
        } catch (e) {}
        return '/gracias/';
    }

    function idDelFormulario(event) {
        if (event.detail && event.detail.contactFormId) {
            return String(event.detail.contactFormId);
        }
        // Respaldo: el campo oculto _wpcf7 que CF7 pone en cada formulario.
        var oculto = event.target.querySelector('input[name="_wpcf7"]');
        return oculto ? String(oculto.value) : '';
    }

    document.addEventListener('wpcf7submit', function(event) {

        if (event.detail && event.detail.apiResponse && event.detail.apiResponse.status === 'mail_sent') {
            if (formulariosSinGracias.indexOf(idDelFormulario(event)) !== -1) {
                return;
            }
            var form = event.target;
            var nameInput = form.querySelector('[name="your-name"]');
            try {
                window.sessionStorage.setItem('burger_gracias_nombre', nameInput ? nameInput.value.trim().slice(0, 60) : '');
            } catch (e) {}
            window.location.href = paginaDeGracias(form);
        }
        
    }, false);

    // Avisos por campo (23/10/2026): los pone CF7 (.wpcf7-not-valid-tip dentro de
    // .wpcf7-form-control-wrap) y el estilo está en styler.css. Acá sólo se suma lo que CF7 no hace:
    // llevar el foco al primer campo con error y, si CF7 no lo puso, asociar el aviso al campo.
    document.addEventListener('wpcf7invalid', function(event) {

        var contenedor = event.target;

        setTimeout(function() {

            var invalidos = contenedor.querySelectorAll('.wpcf7-form-control.wpcf7-not-valid');

            invalidos.forEach(function(control) {
                var campo = control.matches('input, select, textarea') ? control : control.querySelector('input, select, textarea');
                var wrap = control.closest('.wpcf7-form-control-wrap');
                var aviso = wrap ? wrap.querySelector('.wpcf7-not-valid-tip') : null;
                if (!campo || !aviso) return;
                if (!aviso.id) {
                    aviso.id = 'blitz-aviso-' + (campo.name || 'campo').replace(/[^0-9a-z_-]+/ig, '') + '-' + Math.random().toString(36).slice(2, 8);
                }
                var referencias = (campo.getAttribute('aria-describedby') || '').split(/\s+/).filter(Boolean);
                if (referencias.indexOf(aviso.id) === -1) referencias.push(aviso.id);
                campo.setAttribute('aria-describedby', referencias.join(' '));
            });

            var primero = invalidos[0];
            if (primero) {
                var foco = primero.matches('input, select, textarea') ? primero : primero.querySelector('input, select, textarea');
                if (foco) foco.focus();
            }

        }, 0);

    }, false);

});
