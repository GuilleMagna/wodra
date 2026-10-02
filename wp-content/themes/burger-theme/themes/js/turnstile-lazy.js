(function () {
    'use strict';

    var apiPromise;
    var widgets = new WeakMap();
    var pending = new WeakSet();
    var selector = '.wpcf7-turnstile[data-sitekey]';

    function loadApi() {
        if (apiPromise) return apiPromise;
        if (window.turnstile) return Promise.resolve(window.turnstile);
        apiPromise = new Promise(function (resolve, reject) {
            var script = document.createElement('script');
            script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
            script.async = true;
            var timeout = window.setTimeout(fail, 20000);
            function fail() {
                window.clearTimeout(timeout);
                script.remove();
                apiPromise = null;
                reject(new Error('Turnstile no disponible'));
            }
            script.onerror = fail;
            script.onload = function () {
                window.clearTimeout(timeout);
                if (!window.turnstile) return fail();
                window.turnstile.ready(function () { resolve(window.turnstile); });
            };
            document.head.appendChild(script);
        });
        return apiPromise;
    }

    function formFor(target) {
        var form = target.closest && target.closest('form');
        return form && form.querySelector(selector) ? form : null;
    }

    function activate(form) {
        return loadApi().then(function (api) {
            return Array.from(form.querySelectorAll(selector)).map(function (element) {
                if (!widgets.has(element)) {
                    // Mantener los atributos de CF7, incluido el nombre del token.
                    var options = {};
                    Array.from(element.attributes).forEach(function (attribute) {
                        if (attribute.name.indexOf('data-') === 0 && attribute.value !== '') {
                            options[attribute.name.slice(5)] = attribute.value;
                        }
                    });
                    widgets.set(element, api.render(element, options));
                }
                return widgets.get(element);
            });
        });
    }

    function message(form, text) {
        var status = form.querySelector('.burger-turnstile-status');
        if (!status) {
            status = document.createElement('p');
            status.className = 'burger-turnstile-status';
            status.setAttribute('role', 'status');
            form.querySelector(selector).after(status);
        }
        status.textContent = text;
    }

    function onInteraction(event) {
        var form = formFor(event.target);
        if (form) activate(form).catch(function () {
            message(form, 'No se pudo cargar la verificación. Intentá nuevamente.');
        });
    }
    document.addEventListener('focusin', onInteraction);
    document.addEventListener('pointerdown', onInteraction);

    // Captura antes del envío AJAX de CF7, también para autofill o envío con Enter.
    document.addEventListener('submit', function (event) {
        var form = formFor(event.target);
        if (!form) return;
        var elements = Array.from(form.querySelectorAll(selector));
        var ready = window.turnstile && elements.every(function (element) {
            return widgets.has(element) && window.turnstile.getResponse(widgets.get(element));
        });
        if (ready) return;
        event.preventDefault();
        event.stopImmediatePropagation();
        if (pending.has(form)) return;
        pending.add(form);
        var submitter = event.submitter;
        message(form, 'Esperando la verificación de seguridad…');
        activate(form).then(function (ids) {
            var started = Date.now();
            var timer = window.setInterval(function () {
                if (!form.isConnected || Date.now() - started > 60000) {
                    window.clearInterval(timer);
                    pending.delete(form);
                    message(form, 'Completá la verificación y volvé a enviar el formulario.');
                    return;
                }
                if (!ids.every(function (id) { return window.turnstile.getResponse(id); })) return;
                window.clearInterval(timer);
                pending.delete(form);
                message(form, '');
                form.requestSubmit(submitter && submitter.form === form ? submitter : undefined);
            }, 200);
        }).catch(function () {
            pending.delete(form);
            message(form, 'No se pudo cargar la verificación. Intentá nuevamente.');
        });
    }, true);

    // Reiniciar únicamente los widgets del formulario enviado, como requiere CF7.
    document.addEventListener('wpcf7submit', function (event) {
        if (!window.turnstile) return;
        event.target.querySelectorAll(selector).forEach(function (element) {
            if (widgets.has(element)) window.turnstile.reset(widgets.get(element));
        });
    });
})();
