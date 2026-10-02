const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const listeners = {}, scripts = [], renders = [], resets = [], intervals = [];
const tokens = new Map();
const window = { setTimeout: () => 1, clearTimeout() {}, setInterval(fn) { intervals.push(fn); return intervals.length; }, clearInterval() {} };
const document = {
    addEventListener(name, fn) { listeners[name] = fn; },
    createElement() { return { setAttribute() {}, remove() {} }; },
    head: { appendChild(script) { scripts.push(script); } }
};
function makeForm() {
    const element = { attributes: [ { name: 'data-sitekey', value: 'test' }, { name: 'data-response-field-name', value: '_wpcf7_turnstile_response' } ], after(status) { form.status = status; } };
    const form = { isConnected: true, closest() { return this; }, querySelector(s) { return s === '.burger-turnstile-status' ? this.status : element; }, querySelectorAll() { return [element]; }, requestSubmit() { this.submits = (this.submits || 0) + 1; } };
    return form;
}
async function flush() { await new Promise(resolve => setImmediate(resolve)); }
(async () => {
    vm.runInNewContext(fs.readFileSync(require('node:path').join(__dirname, '../themes/js/turnstile-lazy.js'), 'utf8'), { window, document, Promise, WeakMap, WeakSet, Array, Date, Error });
    assert.equal(scripts.length, 0, 'sin descarga inicial');
    const first = makeForm(), second = makeForm();
    listeners.focusin({ target: first });
    listeners.pointerdown({ target: first });
    assert.equal(scripts.length, 1, 'una descarga con interacciones simultáneas');
    window.turnstile = {
        ready(fn) { fn(); },
        render(element, options) { const id = 'widget-' + renders.length; renders.push({ element, options }); return id; },
        getResponse(id) { return tokens.get(id) || ''; },
        reset(id) { resets.push(id); tokens.delete(id); }
    };
    scripts[0].onload();
    await flush();
    assert.equal(renders.length, 1, 'solo el formulario usado');
    assert.equal(renders[0].options['response-field-name'], '_wpcf7_turnstile_response');
    listeners.focusin({ target: first });
    await flush();
    assert.equal(renders.length, 1, 'no duplicar widgets');
    let prevented = false, stopped = false;
    listeners.submit({ target: first, preventDefault() { prevented = true; }, stopImmediatePropagation() { stopped = true; } });
    await flush();
    assert.ok(prevented && stopped, 'no enviar sin token');
    intervals[0]();
    assert.equal(first.submits || 0, 0);
    tokens.set('widget-0', 'token');
    intervals[0]();
    assert.equal(first.submits, 1, 'reanudar cuando está verificado');
    listeners.focusin({ target: second });
    await flush();
    assert.equal(scripts.length, 1);
    assert.equal(renders.length, 2);
    listeners.wpcf7submit({ target: first });
    assert.deepEqual(resets, ['widget-0'], 'reset solo del formulario enviado');
    console.log('OK: carga diferida, concurrencia, render por formulario, envío y reset.');
})().catch(error => { console.error(error); process.exitCode = 1; });
