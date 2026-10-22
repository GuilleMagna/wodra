# SVG Sanitizer

Biblioteca: enshrined/svg-sanitize 1.1.1 (GPL-2.0-or-later).
Fuente: https://github.com/darylldoyle/svg-sanitizer
Commit: 1976ff93c8d8a234cdd3ff99626bd43ecf11bf49

Se distribuyen src/, LICENSE, README.md y composer.json originales, con dos ajustes:
- Namespace enshrined\svgSanitize reemplazado por Wodra\SvgSanitize en src/ para
  evitar cargar accidentalmente una versión antigua de otro plugin.
- Se omite src/svg-scanner.php (ejecutable CLI innecesario para el sitio).

El autoloader está en inc/site-svg.php; no hace falta Composer en producción.
Al actualizar, repetir estos ajustes y ejecutar tests/site-svg-smoke.php.
