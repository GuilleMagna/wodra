/*
 * Flotante (.botones-flotantes): se oculta cuando el botón de envío del formulario del pie entra
 * en pantalla y no vuelve mientras se sigue bajando (tampoco sobre el pie). Vuelve si se sube y el
 * botón queda otra vez por debajo de la pantalla. Sólo pone o saca la clase blitz-form-visible en
 * <body>; el ocultado está en themes/css/styler.css. Sin JS el flotante queda visible.
 * Sin dependencias; funciona cargado con o sin defer. blitz. 23/10/2026
 */
(function () {
    'use strict';

    var CLASE = 'blitz-form-visible';
    var boton = null;

    function buscarBoton() {
        var forms = document.querySelectorAll('.formulario .wpcf7 form');
        var form = forms.length ? forms[forms.length - 1] : null;
        if (!form) return null;
        // ENVIAR puede ser el <button> que pone blocks/formulario/scripts.js o el input original de CF7.
        return form.querySelector('button[type="submit"], .wpcf7-submit');
    }

    function revisar() {
        if (!boton || !boton.isConnected) boton = buscarBoton();
        var oculto = false;
        if (boton) {
            var caja = boton.getBoundingClientRect();
            // En pantalla o ya pasado: borde superior por encima del final de la ventana.
            // Un botón sin tamaño (formulario oculto) cuenta como si no hubiera formulario.
            oculto = (caja.width > 0 || caja.height > 0) && caja.top < window.innerHeight;
        }
        if (document.body) document.body.classList.toggle(CLASE, oculto);
    }

    function iniciar() {
        var pendiente = false;
        function programar() {
            if (pendiente) return;
            pendiente = true;
            window.requestAnimationFrame(function () { pendiente = false; revisar(); });
        }
        window.addEventListener('scroll', programar, { passive: true });
        window.addEventListener('resize', programar, { passive: true });
        window.addEventListener('load', revisar);
        revisar();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', iniciar);
    } else {
        iniciar();
    }
})();
