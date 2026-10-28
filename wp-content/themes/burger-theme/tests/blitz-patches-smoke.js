/* Ejecutar con node. Contextos aislados: no navegador, red ni envíos reales. */
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const theme = path.resolve(__dirname, '..');
const listeners = {};
const document = { addEventListener: (name, fn) => { listeners[name] = fn; } };
const stored = new Map();
const window = {
    location: { href: 'https://example.test/origen/', origin: 'https://example.test' },
    sessionStorage: { setItem: (key, value) => stored.set(key, value) }
};
const chain = { find() { return this; }, attr() {}, replaceWith() {} };
const jquery = value => value === document ? { ready: fn => fn(jquery) } : chain;
vm.runInNewContext(fs.readFileSync(path.join(theme, 'blocks/formulario/scripts.js'), 'utf8'), {
    document, window, URL, jQuery: jquery, setTimeout: fn => fn()
});
for (const id of [802, 1033, 396, 11811]) {
    for (const detailId of [true, false]) {
        window.location.href = 'https://example.test/origen/';
        const target = { closest: () => null, querySelector: selector => ({ value: selector.includes('_wpcf7') ? String(id) : 'Ana Pérez' }) };
        listeners.wpcf7submit({ target, detail: { apiResponse: { status: 'mail_sent' }, ...(detailId ? { contactFormId: id } : {}) } });
        assert.equal(window.location.href, [802, 1033].includes(id) ? 'https://example.test/origen/' : 'https://example.test/gracias/');
        if (![802, 1033].includes(id)) assert.equal(stored.get('burger_gracias_nombre'), 'Ana Pérez');
        window.location.href = 'https://example.test/origen/';
        listeners.wpcf7submit({ target, detail: { apiResponse: { status: 'validation_failed' }, contactFormId: id } });
        assert.equal(window.location.href, 'https://example.test/origen/');
    }
}
function submitTo(destination, name) {
    window.location.href = 'https://example.test/origen/';
    listeners.wpcf7submit({
        target: {
            closest: () => destination ? { getAttribute: () => destination } : null,
            querySelector: () => name === null ? null : { value: name }
        },
        detail: { contactFormId: 396, apiResponse: { status: 'mail_sent' } }
    });
}
submitTo('/confirmacion/', null);
assert.equal(window.location.href, 'https://example.test/confirmacion/');
assert.equal(stored.get('burger_gracias_nombre'), '', 'borrar nombre anterior si no existe el campo');
submitTo('https://otro.test/', '  ' + 'A'.repeat(90) + '  ');
assert.equal(window.location.href, '/gracias/', 'rechazar destino externo');
assert.equal(stored.get('burger_gracias_nombre').length, 60);
window.sessionStorage.setItem = () => { throw new Error('storage bloqueado'); };
submitTo('/confirmacion/', 'Ana');
assert.equal(window.location.href, 'https://example.test/confirmacion/', 'redirigir incluso sin sessionStorage');
const titleScript = fs.readFileSync(path.join(theme, 'blocks/descripcion-empresa/content.php'), 'utf8').match(/<script>([\s\S]*?)<\/script>/)[1];
const nameElement = { textContent: '', set innerHTML(value) { throw new Error('HTML inseguro'); } };
const hostileName = '<img src=x onerror=alert(1)>';
vm.runInNewContext(titleScript, {
    window: { sessionStorage: { getItem: () => hostileName } },
    document: { querySelectorAll: () => [nameElement] }
});
assert.equal(nameElement.textContent, ' ' + hostileName, 'el nombre se inserta como texto');
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
