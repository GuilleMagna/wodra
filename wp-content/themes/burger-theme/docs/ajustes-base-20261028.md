# Ajustes incorporados a BASE — 28/10/2026

Los parches recibidos se revisaron contra este theme. Se trasladaron las correcciones generales y se reemplazó la configuración del cliente original por una opción reutilizable.

## Aplicado

- Turnstile lazy: al cargar la API se resuelve la promesa sin llamar a `ready()` con el script async. Se conservan la carga por interacción, el token de CF7 y el reset por formulario. Referencia: [renderizado explícito de Cloudflare](https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/).
- Formularios: los nombres ya no se agregan a la URL. Se guardan por pestaña en sessionStorage, con límite de 60 caracteres. El envío correcto redirige también si no existe el campo de nombre o el navegador bloquea storage. Los formularios de newsletter 802 y 1033 conservan su comportamiento sin redirección.
- El bloque Formulario incorpora el selector opcional **Página de gracias**. Sin seleccionar una página, utiliza la ruta de gracias bajo la URL principal de WordPress. No se incluyeron mapas con slugs específicos del cliente. Los destinos externos se rechazan en el cliente.
- Descripción empresa: `[nombre]` se convierte en un marcador cuyo contenido se asigna mediante textContent. Se ignora el parámetro `nombre` de la URL, incluso si llega como array. Sin nombre guardado el marcador queda vacío.
- Eliminado el flujo antiguo de envío manual y reCAPTCHA: no tenía hooks de envío activos ni referencias desde los bloques actuales. CF7 mantiene la responsabilidad del correo y de la verificación. La API antigua de Google deja de cargarse globalmente.
- El deploy excluye `docs/` y `tests/`, además de conservar la exclusión del caché. Un .htaccess en la raíz del theme bloquea por HTTP esas dos rutas, incluyendo copias que hayan quedado de despliegues anteriores. No se modificó DEPLOYPATH ni se amplió el alcance de rsync.

## No trasladado

- Los mapas de páginas de agradecimiento del cliente: ahora se configuran en cada instancia del bloque Formulario.
- Pedidos editoriales sobre textos, imágenes y secciones del cliente: la base usa contenido genérico.
- Fondos externos de Grupo SIT: ya estaban sustituidos por gradientes en esta rama.
- Los otros puntos de auditoría enumerados en el documento necesitan revisión propia. No se modificaron registro público, paginación AJAX, videos, carruseles ni sus IDs. La escritura de bundles en cache/ y las etiquetas cortas pertenecen a la arquitectura actual y no se cambiaron.

## Validación

- `node tests/turnstile-lazy-smoke.cjs`: API async que rechaza ready(), concurrencia, widgets, token, reenvío y reset.
- `node tests/blitz-patches-smoke.js`: newsletters, destino configurable, errores, ausencia de nombre, storage bloqueado, destinos externos y personalización como texto; conserva las comprobaciones de accesibilidad y del flotante.
- `php -d short_open_tag=1 tests/thank-you-security-smoke.php`: render aislado con nombre malicioso y parámetro array.
- `php -d short_open_tag=1 tests/blitz-patches-smoke.php`: integración ACF, formulario y limpieza del código heredado.
- Lint PHP y comprobaciones HTTP locales: Contacto y Gracias responden 200; el payload no se imprime como script; docs y tests responden 403; reCAPTCHA antiguo ausente del HTML.

No se enviaron formularios reales ni correos, ni se modificaron registros de contenido. No se desplegó a producción.

## Acción externa pendiente

La clave antigua de reCAPTCHA debe revocarse desde la cuenta de Google que la administra: eliminarla del código actual no la elimina del historial público de Git. La revocación y la reescritura del historial no forman parte de este cambio.
