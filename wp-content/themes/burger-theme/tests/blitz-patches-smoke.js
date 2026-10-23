/* Ejecutar con node. Contextos aislados: no navegador, red ni envíos reales. */
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const theme = path.resolve(__dirname, '..');
const listeners = {};
const document = { addEventListener: (name, fn) => { listeners[name] = fn; } };
const window = { location: { href: '/origen/' } };
const chain = { find() { return this; }, attr() {}, replaceWith() {} };
const jquery = value => value === document ? { ready: fn => fn(jquery) } : chain;
vm.runInNewContext(fs.readFileSync(path.join(theme, 'blocks/formulario/scripts.js'), 'utf8'), {
    document, window, jQuery: jquery, setTimeout: fn => fn()
});
for (const id of [802, 1033, 396, 11811]) {
    for (const detailId of [true, false]) {
        window.location.href = '/origen/';
        const target = { querySelector: selector => ({ value: selector.includes('_wpcf7') ? String(id) : 'Ana Pérez' }) };
        listeners.wpcf7submit({ target, detail: { apiResponse: { status: 'mail_sent' }, ...(detailId ? { contactFormId: id } : {}) } });
        assert.equal(window.location.href, [802, 1033].includes(id) ? '/origen/' : '/gracias/?nombre=Ana%20P%C3%A9rez');
        window.location.href = '/origen/';
        listeners.wpcf7submit({ target, detail: { apiResponse: { status: 'validation_failed' }, contactFormId: id } });
        assert.equal(window.location.href, '/origen/');
    }
}
const tip = { id: '' };
const attrs = { 'aria-describedby': 'ayuda-existente' };
let focused = false;
const input = {
    name: 'your-email', matches: () => true,
    closest: () => ({ querySelector: () => tip }),
    getAttribute: key => attrs[key], setAttribute: (key, value) => { attrs[key] = value; },
    focus: () => { focused = true; }
};
for (let i = 0; i < 2; i++) listeners.wpcf7invalid({ target: { querySelectorAll: () => [input] } });
assert(focused);
assert.deepEqual(attrs['aria-describedby'].split(' '), ['ayuda-existente', tip.id]);
console.log('OK: newsletters sin redirección, consultas con redirección, errores sin redirección y asociación accesible sin duplicados.');

const floatingEvents = {};
let top = 900;
let connected = true;
let hasFooterForm = true;
let hidden = false;
const button = { get isConnected() { return connected; }, getBoundingClientRect: () => ({ width: 120, height: 40, top }) };
const floatingDocument = {
    readyState: 'complete',
    body: { classList: { toggle: (name, value) => { hidden = value; } } },
    querySelectorAll: selector => {
        assert.equal(selector, '.formulario .wpcf7 form');
        return hasFooterForm ? [{ querySelector: () => button }] : [];
    }
};
vm.runInNewContext(fs.readFileSync(path.join(theme, 'blocks/botones-flotantes/scripts.js'), 'utf8'), {
    document: floatingDocument,
    window: { innerHeight: 844, addEventListener: (name, fn) => { floatingEvents[name] = fn; }, requestAnimationFrame: fn => fn() }
});
assert.equal(hidden, false);
for (const position of [843, 400, -400]) { top = position; floatingEvents.scroll(); assert.equal(hidden, true); }
top = 900; floatingEvents.scroll(); assert.equal(hidden, false);
connected = false; hasFooterForm = false; floatingEvents.scroll(); assert.equal(hidden, false);
console.log('OK: flotante se oculta al llegar al envío, sigue oculto al bajar, reaparece al subir y no usa newsletters como respaldo.');
