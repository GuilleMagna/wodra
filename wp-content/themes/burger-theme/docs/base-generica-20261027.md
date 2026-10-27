# Base de demostración local — 27/10/2026

Se convirtió la copia local a una base genérica, por autorización del usuario que amplió el alcance a contenido de WordPress, opciones y medios.

- Nombre público: Sitio Demo. Textos lorem ipsum en páginas, noticias, eventos, presets, plantillas, revisiones y campos ACF.
- Se conservaron IDs, relaciones y ajustes de diseño. Se actualizaron slugs y enlaces internos a rutas genéricas, conservando la jerarquía de páginas.
- 416 adjuntos reemplazados por composiciones abstractas WebP y marcas de demostración: aproximadamente 1,45 MB en total.
- 77 imágenes pesadas del theme pasaron de 50.797.800 a 414.578 bytes antes de la sustitución adicional de 21 logos y distintivos de fallback.
- Originales de uploads archivados: 1.592 archivos / 552.322.661 bytes. Los archivos originales ya no están en la biblioteca activa.
- Contactos, redes y mapas externos sustituidos por valores de ejemplo. Formularios de demostración con `skip_mail: on`; destinatarios de ejemplo.
- Identidad pública de autores genérica; accesos y contraseñas conservados. Índices derivados de Yoast invalidados para regenerarse desde el contenido nuevo.
- Se desactivaron Próximamente e idiomas en esta copia para mostrar la demo genérica.
- Tres registros del tipo no registrado `servicio` (5975, 5995, 5996), que respondían 404, quedaron como borradores.
- `llms.txt` local reemplazado. Materiales de trabajo antiguos y volcados SQL que estaban en la raíz archivados con el respaldo.

## Verificación

45 URLs públicas activas respondieron 200, sin referencias a la identidad anterior en el HTML revisado. Todos los adjuntos tienen archivo existente. Se revisaron visualmente portada, Nosotros y Contacto. El respaldo devuelve 403 por HTTP.

Respaldo local: `C:/localhost/wodra/.generic-backup-20261027-144412/`.
Incluye base completa anterior (`database.sql`), medios originales (`uploads/`), imágenes originales del theme (`theme-images/`), materiales anteriores (`legacy-files/`) y scripts ejecutados (`scripts/`). Está protegido por `.htaccess` y no se versiona.

## Alcance de Git

Los cambios de base de datos, `uploads/` y `llms.txt` son locales y están ignorados por el repositorio. El deploy existente por Git sólo copia el theme; no transporta estos cambios de contenido. No se desplegó a producción.
