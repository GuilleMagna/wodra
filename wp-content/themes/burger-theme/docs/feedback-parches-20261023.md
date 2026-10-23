# Integración de los parches del 23/10/2026

Origen: `web-wodra-para-guille-20261023.zip`, parche completo y parche 2.
Se integraron sobre el theme actual, conservando los cambios de Novedades ya publicados en Git.

## Aplicado

- Eliminación del envoltorio global de correo de Andemira. En local el filtro ya estaba comentado; ahora se elimina también la función.
- Conservación de los contenedores span de CF7 para permitir avisos de validación. Se mantiene la expresión que elimina párrafos sin afectar path ni pre.
- Newsletters 802 y 1033 sin redirección; consultas mantienen la redirección previa a Gracias.
- Foco en el primer campo inválido y asociación al aviso, conservando los identificadores previos de aria-describedby sin duplicarlos.
- Alturas y estilos de formularios, select, errores y términos. Los cuatro formularios y la página Contacto 10155 existen en la base local.
- Flotante sin bloquear clics fuera de sus botones; se oculta al llegar al envío del último bloque Formulario y reaparece al subir. No usa cualquier newsletter como respaldo. Las mediciones de scroll se agrupan por frame.
- Recorte centrado con object-fit: cover en Hero Video y Parallax Video.
- Encabezados de Equipo, pie y menú móvil; títulos e identificadores accesibles de modales.
- Corrección del cierre div del sub-footer y copyright de respaldo con nombre del sitio y año actual; se respeta el copyright configurado.
- Nuevos fondos locales de bg-green-image y bg-count-image, cuyos archivos existen.

## Correcciones respecto del ZIP para devolver como feedback

1. **Teléfonos de WhatsApp:** el parche aceptaba números de ocho o nueve dígitos y recortaba los largos a los últimos diez, pudiendo fabricar un destino equivocado. Se aceptan diez dígitos nacionales, con los prefijos argentinos habituales, y se rechazan números incompletos, internacionales de otros países, extensiones y formatos ambiguos. Es validación de formato, no comprobación de existencia del número ni de su cuenta de WhatsApp. Los números sin característica quedan pendientes de que el usuario los complete.
2. **Marcado real de los formularios:** ninguno de los cuatro formularios locales tiene la clase blitz-tyc. Los newsletters usan form-check con un checkbox HTML. Los estilos ahora admiten ambos contenedores, sin modificar formularios en la base. Las etiquetas for/id de varios campos y los IDs repetidos de acceptance-legales siguen siendo un pendiente del contenido de CF7, fuera de estos archivos del theme.
3. **Validación de términos:** un checkbox HTML required no equivale a un campo acceptance validado por CF7. Si se necesita validación de aceptación en el servidor, debe corregirse la definición del formulario; estos parches no la cambian.
4. **Contraste:** subrayar no corrige el contraste. Sobre blanco se usa azul oscuro; sobre celeste, texto y enlaces #10243a; sobre azul oscuro, enlaces #8ab8ff subrayados. Se deben revisar fondos fotográficos reales por separado.
5. **Foco:** cambiar solo border-color no alcanza en campos border-0 y forzar fondo blanco puede ocultar texto blanco. Se agregó outline visible sin sustituir el fondo del campo.
6. **Modales:** los z-index altos se limitan a modal-menu-footer y a su backdrop cuando está abierto, evitando elevar todos los modales del sitio.
7. **Flechas de sucursales:** se reutilizan los SVG existentes inline, conservando su dibujo y tamaño. No dependen de gruposit ni de una ruta fija a wp-content.
8. **Copyright:** se descartó el respaldo genérico Copyright 2025 Cliente a favor del nombre y año del sitio.

## Sustituciones del ZIP no aplicadas

- `bg-video::before`: Rectangle-411.png no existe en uploads local.
- `bg-conoce-image`: Amasado00145.jpg no existe en uploads local.
- `bg-video`: un MP4 no funciona como background-image CSS. Debe configurarse un bloque de video o aportarse una imagen de respaldo.

En estas tres reglas se conservaron las declaraciones anteriores. Esto significa que sus referencias heredadas a gruposit siguen pendientes; no se consideran corregidas. Se necesita confirmar los recursos y el uso real de esas clases antes de reemplazarlas. No se modificó uploads.

## Validación y límites

- Sintaxis PHP de los archivos modificados y sintaxis JavaScript: correctas.
- `tests/blitz-patches-smoke.php`: pasa. Comprueba spans/SVG/pre, ausencia del envoltorio de correo, formatos de teléfono y existencia de formularios/página en local. Solo lectura y con carga de plugins limitada a ACF/SCF en ese proceso.
- `tests/blitz-patches-smoke.js`: pasa. Simula eventos de envío/validación y scroll; comprueba newsletters, redirecciones, foco, asociaciones accesibles y flotante. Sin navegador ni red.
- El navegador local anónimo muestra Próximamente. No se desactivó esa configuración ni se realizó una revisión visual completa del sitio.
- No se enviaron correos, no se completó Turnstile ni se modificaron formularios o metadatos en la base.
- El PHP CLI de XAMPP es 8.2 y Contact Form 7 instalado exige 8.3; por ello las pruebas PHP aíslan ACF/SCF y no cargan CF7. Falta verificar envíos y presentación real con sesión autorizada y el entorno PHP compatible.
- Las pruebas declaradas en los LEEME del ZIP no se presentan como pruebas realizadas aquí.

La publicación en producción sigue el procedimiento habitual: commit y push, luego Pull or Deploy desde cPanel.
