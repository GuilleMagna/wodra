# Edita Fácil en Wodra

Integración de la fase 1 de la guía de Mariano del 2 de noviembre de 2026.
La interfaz y sus estilos parten de Edita Fácil de Nakama v1.2.0, suministrado
por Guille. Los archivos conservan la autoría de Nakama y su licencia propietaria.
El almacenamiento de bloques es específico de este theme.

## Uso

En **Configuración General → Edita Fácil** se puede apagar la función para todos
con **Activar Edita Fácil**, o elegir sus **Roles habilitados**. Los administradores
también deben estar marcados. Sin roles seleccionados nadie puede usarla. Habilitar
un rol no le otorga permiso para editar páginas que WordPress no le permite editar.
El apagado bloquea también formularios abiertos y accesos directos; la visibilidad
por dispositivo ya guardada se conserva. Sin configuración guardada se mantiene
el funcionamiento anterior.

1. Iniciar sesión en WordPress con un usuario que pueda editar la página.
2. Visitar la página y pulsar **Editar la página** en la barra inferior.
   `?editar=1` también activa el modo.
3. Pulsar **Editar** sobre una sección, cambiar sus campos y guardar.
   La página se recarga conservando la posición.
4. En el formulario, marcar los dispositivos donde se desea ocultar la sección.
   En modo edición las secciones ocultas mantienen un borde naranja.

La edición de Gutenberg sigue disponible. El formulario muestra los campos ACF
del bloque, incluidos sus controles de diseño y sus condiciones habituales.
Si el bloque usa un preset de diseño, los valores visibles siguen dependiendo de
ese preset mientras permanezca seleccionado.

## Alcance y despliegue

- Todo vive en `inc/edita-facil/`, cargado desde `functions.php`.
  No instalar el ZIP en `plugins/`: esta integración utiliza su propia ruta
  de formulario, `admin.php?page=burger-edita-facil`.
- No cambia roles, login, panel de clientes, gestores de fichas ni categorías.
  Tampoco implementa agregar, mover, duplicar o eliminar bloques desde el front
  (fases 2 y 3 de la propuesta).
- No modifica los `content.php` ni las preferencias de caché del sitio.
- Deploy habitual por Git y cPanel, junto con el resto del theme.

## Almacenamiento y compatibilidad

`efid` identifica una instancia y `efHide` guarda sus dispositivos ocultos.
Ambos atributos están registrados en PHP y en Gutenberg. Los IDs se asignan al
guardar y se regeneran para duplicados dentro del documento.

Las páginas anteriores usan temporalmente un localizador con posición y huella
del bloque. No hay migraciones masivas ni escrituras al visitar la web. El primer
guardado desde el front persiste los IDs y actualiza la URL de retorno.

El formulario carga los datos con `acf_setup_meta()`. El guardado utiliza su
captura nativa (la misma que usa ACF al serializar los bloques), manteniendo los
repetidores y grupos anidados en formato aplanado. Reemplaza solo la instancia
elegida y guarda con `wp_update_post()`, con revisiones y la invalidación de
transients que ya realiza el theme. Los campos no se guardan en postmeta.

Los marcadores se agregan en `render_block`, después de la caché del theme.
Solo aparecen con permiso `edit_post` sobre el documento verificado como dueño.
El público recibe únicamente la visibilidad, nunca los controles ni el script
del editor. Las secciones sintéticas de la estructura fija, los bloques que
leen contenido de presets y los bloques cuyo dueño no se puede verificar no
muestran botón: sus fuentes compartidas se siguen administrando desde WordPress.

Cada envío requiere nonce de ACF, nonce específico del destino, campos del grupo
del bloque, permiso vigente y una huella del contenido sin cambios desde la
apertura. Se consulta también `wp_check_post_lock()`. Un formulario viejo pide
reabrir la sección. Esto no evita que alguien deje Gutenberg abierto y más tarde
guarde allí una copia vieja; conviene usar una sola vía de edición por página.

## Verificación

Desde la raíz del sitio, con MariaDB de XAMPP en ejecución:

```powershell
C:\xampp\php84\php.exe -d short_open_tag=1 wp-content/themes/burger-theme/tests/edita-facil-smoke.php
```

La prueba usa ACF real, grupos de campos solo en memoria y una página descartable
propia que elimina junto con sus revisiones al terminar. Comprueba edición de
instancias repetidas, grupos y repetidores anidados, caracteres y escapes,
imágenes, visibilidad, envío firmado de SCF, permisos, nonce, conflictos,
revisiones, marcadores públicos y primer guardado sin IDs previos.

Para la comprobación visual iniciar sesión manualmente y recorrer un bloque con
texto, uno con imagen y uno con repetidores, también en móvil. No se automatiza
el ingreso de credenciales.
