# Wodra — Kehilá (comunidad judía de Rosario)

Sitio WordPress. El trabajo se hace **exclusivamente sobre el theme**
`wp-content/themes/burger-theme`. WordPress core, plugins de terceros y `uploads/`
no se versionan ni se tocan.

- **PRD:** https://web.wodra.ar
- **Local:** http://wodra.local.com (XAMPP)
- **Repo:** https://github.com/GuilleMagna/wodra (público, rama `master`)

El theme es una variante de "burger-theme", el mismo base que usan los proyectos
`burger` (`C:/localhost/burger`) y `dcm` (`C:/localhost/dcm`). Los tres divergieron:
un fix en uno **no** se puede copiar y pegar en otro sin revisar.

## Deploy

Nunca subir archivos a mano por FTP — así se perdieron cambios antes.

```
editar local → git commit → git push → cPanel > Git Version Control > Pull or Deploy
```

`.cpanel.yml` hace un `rsync` del theme. El `DEPLOYPATH` apunta **solo al directorio
del theme**, no al docroot: eso es lo que hace seguro el `--delete`. Si alguna vez
se cambia a apuntar al docroot, `--delete` borraría WordPress core, plugins y uploads,
porque nada de eso está en este repo.

`.gitignore` usa patrones de negación para trackear únicamente
`wp-content/themes/burger-theme/`. Al agregar cualquier archivo fuera de esa ruta hay
que sumar su propio `!` explícito, si no queda ignorado en silencio.

## Arquitectura

`functions.php` carga, en orden: `inc/tools.php`, `inc/setup.php`, `inc/blocks.php`,
`inc/assets.php`.

Cada bloque vive en `blocks/<slug>/` con `content.php`, `styles.css` y a veces
`scripts.js`. Se registran con `acf_register_block()` desde `burger_extend_blocks()`
en `inc/blocks.php`.

Los campos se leen con `get_block_content_fields( $block, [ 'campo', ... ] )` seguido de
`extract()`, y el diseño con `get_block_design( $block )`. Los estilos por instancia se
imprimen en un `<style>` inline scopeado con `$block['id']`.

**Convención para JS de bloque:** el init va inline en `content.php`, no en `scripts.js`
(así se pueden interpolar los valores ACF). `scripts.js` queda solo para lógica que no
depende de campos. `conoce-mas` es el bloque de referencia.

### `inc/assets.php` — bundler

Concatena vendors + CSS del theme en un único archivo bajo `cache/`
(gitignorado, se regenera solo). Reescribe los `url()` del CSS a **protocolo-relativo**
(`//dominio/...`) para no depender de `is_ssl()`, que miente detrás de proxy/CDN.

### `inc/blocks.php` — caché de bloques

El HTML renderizado de cada bloque se guarda en transients `burger_block_*`. Se controla
desde *Configuración General* (ACF options) con un toggle de on/off y otro de limpieza
manual. Se purga solo en cada `save_post`.

## Trampas conocidas (todas costaron tiempo)

- **`acf/init` corre ANTES de `functions.php`** en esta instalación: ACF arranca con los
  plugins, que preceden al theme. Envolver `acf_add_local_field_group()` en
  `add_action('acf/init', ...)` hace que **nunca se ejecute**. Llamarlo directo en el
  nivel superior, guardado solo por `function_exists()`.

- **`default_value` de ACF no aplica retroactivamente.** Si se agrega un campo nuevo a un
  bloque ya guardado en una página, esa instancia nunca tuvo la clave `_campo` grabada y
  `get_field()` devuelve vacío — el default no se resuelve. Hay que poner un fallback
  explícito en PHP después de `get_block_content_fields()`. Ver `blocks/galeria/content.php`.

- **`wp_add_inline_style()` exige un handle registrado.** Si no existe, falla en silencio.
  Patrón correcto: `wp_register_style($h, false)` → `wp_enqueue_style($h)` → `wp_add_inline_style($h, $css)`.

- **El editor de bloques acá NO es iframe** (los metaboxes clásicos de ACF/Yoast lo fuerzan
  a modo no-iframe). Consecuencia: el JS y el CSS del editor corren en el documento
  top-level de wp-admin. Por eso hay que desregistrar el estilo `colors` de WP en pantallas
  de editor, y forzar visibles los elementos `[data-aos]` (sus librerías nunca corren ahí).

- **Orden de hooks en el editor:** `add_editor_dynamic_styles()` va con prioridad **20**
  en `enqueue_block_editor_assets`. En 10 lo pisa el `--primary` estático de `colors.css`.

- **Owl Carousel:** `initialized.owl.carousel` se dispara **sincrónicamente adentro** de la
  llamada a `.owlCarousel()`, y `$el.data('owl.carousel')` recién existe **después**. Los
  listeners se atan **antes** de instanciar, y los items por breakpoint se calculan con una
  función propia que espeja el `responsive`, no consultando la instancia.

- **Centrar el `.owl-stage`** dentro de un `.owl-stage-outer` flex necesita
  `flex: 0 0 auto` además de `width: auto`. Sin eso el stage se estira igual.

- **`short_open_tag` tiene que estar en `On`.** Hay ~89 `<? ... ?>` repartidos en 21
  archivos. PRD ya lo tiene activo; en local se resuelve con `php_admin_flag short_open_tag on`
  en el vhost. **No** convertir los tags uno por uno.

- **Regex y límites de palabra:** `/<p.*?>/` matchea `<path>`. Usar
  `/<p(?:\s[^>]*)?>/`. Este bug hacía desaparecer los íconos SVG de Contact Form 7
  (el culpable estaba en `inc/setup.php`, no en CF7).

## Entorno local

- XAMPP: Apache + MariaDB. El vhost está en `C:\xampp\apache\conf\extra\httpd-vhosts.conf`.
- Apache no es servicio: se reinicia matando y relanzando `httpd.exe`.
- MySQL: `/c/xampp/mysql/bin/mysql.exe -u root` (sin password).

## Reglas de trabajo

- Nunca escribo credenciales — el login a wp-admin y a cPanel lo hace el usuario.
- No probar de forma destructiva sobre filas que puedan preexistir en la base; usar
  registros descartables o simular el contexto en un script aislado.
