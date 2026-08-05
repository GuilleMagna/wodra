# Bloque Burger: Tabla de datos

Carpeta lista para copiar en:

```txt
/wp-content/themes/tu-tema/blocks/tabla-datos/
```

## Archivos

- `content.php`: render del bloque y parser interno de CSV/XLSX/manual.
- `styles.css`: estilos mínimos del bloque.
- `scripts.js`: inicialización DataTables con fallback simple de búsqueda si DataTables no está cargado.
- `fields.json`: grupo ACF para importar.
- `content.png`: preview para el editor.

## Dependencias

- ACF PRO.
- Bootstrap 5 recomendado.
- DataTables opcional. Si no existe `$.fn.DataTable`, el bloque muestra la tabla normal y agrega buscador básico.
- Para Excel `.xlsx`, PHP necesita `ZipArchive` y `SimpleXML`.

## Dataset CSV/XLSX

Si la primera fila contiene encabezados, las columnas se normalizan así:

```txt
Precio Socio -> precio_socio
Precio No Socio -> precio_no_socio
```

En el repeater de columnas usá esas keys.

## Dataset manual

1. Cargá columnas con `Nombre` y `Key`.
2. En cada fila manual, agregá celdas con la misma `Key` y su valor.

## Estilos

El bloque usa el grupo global `Diseño de Bloque` de Burger para padding, margen, color de fondo, colores principales, ancho y alineación. Solo agrega variables propias para encabezado, hover, borde y striped de tabla.
