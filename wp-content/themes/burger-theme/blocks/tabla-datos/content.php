<?php
// Block Name: Tabla de datos

if (!defined('ABSPATH')) {
    exit;
}

$block_id = $block['id'] ?? uniqid('tabla-datos-');

$content_fields = [
    'titulo',
    'subtitulo',
    'descripcion',
    'origen_datos',
    'archivo_dataset',
    'primera_fila_encabezados',
    'hoja_excel',
    'columnas',
    'filas_manual',
    'mostrar_buscador',
    'mostrar_paginacion',
    'mostrar_info',
    'filas_por_pagina',
    'orden_columna',
    'orden_direccion',
    'texto_buscar',
    'texto_sin_resultados',
    'mostrar_boton',
    'boton',
    'boton_estilo',
    'table_header_background',
    'table_header_color',
    'table_row_hover',
    'table_striped',
    'table_border',
];

if (function_exists('get_block_content_fields')) {
    $fields = get_block_content_fields($block, $content_fields);
    extract($fields);
} else {
    foreach ($content_fields as $field_name) {
        ${$field_name} = get_field($field_name) ?: '';
    }
}

$design = function_exists('get_block_design') ? get_block_design($block) : [];
extract($design);

$class_container         = $class_container ?? 'container';
$col_lg_container_class = $col_lg_container_class ?? 'col-lg-12';
$col_md_container_class = $col_md_container_class ?? 'col-md-12';
$col_container_class    = $col_container_class ?? 'col-12';
$text_align_class       = $text_align_class ?? 'text-start';
$section_padding        = $section_padding ?? '6rem 0';
$section_margin         = $section_margin ?? '0 auto';
$border_radius          = $border_radius ?? '0';
$color_fondo            = $color_fondo ?? '';
$imagen_fondo           = $imagen_fondo ?? '';
$color_primario         = $color_primario ?: '#1e73be';
$color_secundario       = $color_secundario ?: '#ffffff';

if (!function_exists('burger_td_slug')) {
    function burger_td_slug($text) {
        $text = remove_accents((string) $text);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9_\-]+/', '_', $text);
        return trim($text, '_');
    }
}

if (!function_exists('burger_td_get_file_path')) {
    function burger_td_get_file_path($file) {
        if (empty($file)) return '';
        if (is_numeric($file)) return get_attached_file((int) $file);
        if (is_array($file)) {
            if (!empty($file['ID'])) return get_attached_file((int) $file['ID']);
            if (!empty($file['id'])) return get_attached_file((int) $file['id']);
            if (!empty($file['url'])) {
                $uploads = wp_get_upload_dir();
                return str_replace($uploads['baseurl'], $uploads['basedir'], $file['url']);
            }
        }
        if (is_string($file)) return $file;
        return '';
    }
}

if (!function_exists('burger_td_parse_csv')) {
    function burger_td_parse_csv($path, $has_headers = true) {
        if (!$path || !file_exists($path)) return ['columns' => [], 'rows' => []];

        $delimiter = ';';
        $sample = file_get_contents($path, false, null, 0, 2048);
        if (substr_count($sample, ',') > substr_count($sample, ';')) $delimiter = ',';

        $handle = fopen($path, 'r');
        if (!$handle) return ['columns' => [], 'rows' => []];

        $headers = [];
        $rows = [];
        $line = 0;

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            $data = array_map(function($value) {
                return trim((string) $value);
            }, $data);

            if ($line === 0) {
                if ($has_headers) {
                    foreach ($data as $header) {
                        $key = burger_td_slug($header);
                        $headers[$key] = $header;
                    }
                    $line++;
                    continue;
                }

                foreach ($data as $index => $value) {
                    $key = 'columna_' . ($index + 1);
                    $headers[$key] = 'Columna ' . ($index + 1);
                }
            }

            $row = [];
            $keys = array_keys($headers);
            foreach ($keys as $index => $key) {
                $row[$key] = $data[$index] ?? '';
            }
            $rows[] = $row;
            $line++;
        }

        fclose($handle);
        return ['columns' => $headers, 'rows' => $rows];
    }
}

if (!function_exists('burger_td_xlsx_cell_refs_from_range')) {
    function burger_td_xlsx_cell_refs_from_range($ref) {
        $ref = strtoupper((string) $ref);
        if (strpos($ref, ':') === false) return [$ref];

        [$start, $end] = explode(':', $ref, 2);
        if (!preg_match('/^([A-Z]+)(\d+)$/', $start, $start_matches)) return [$ref];
        if (!preg_match('/^([A-Z]+)(\d+)$/', $end, $end_matches)) return [$ref];

        $col_to_num = function($letters) {
            $num = 0;
            for ($i = 0; $i < strlen($letters); $i++) {
                $num = $num * 26 + (ord($letters[$i]) - 64);
            }
            return $num;
        };

        $num_to_col = function($num) {
            $letters = '';
            while ($num > 0) {
                $mod = ($num - 1) % 26;
                $letters = chr(65 + $mod) . $letters;
                $num = (int) (($num - $mod) / 26);
            }
            return $letters;
        };

        $start_col = $col_to_num($start_matches[1]);
        $end_col   = $col_to_num($end_matches[1]);
        $start_row = (int) $start_matches[2];
        $end_row   = (int) $end_matches[2];

        $refs = [];
        for ($row = min($start_row, $end_row); $row <= max($start_row, $end_row); $row++) {
            for ($col = min($start_col, $end_col); $col <= max($start_col, $end_col); $col++) {
                $refs[] = $num_to_col($col) . $row;
            }
        }

        return $refs;
    }
}

if (!function_exists('burger_td_value_text')) {
    function burger_td_value_text($value) {
        if (is_array($value)) return (string) ($value['text'] ?? '');
        return (string) $value;
    }
}

if (!function_exists('burger_td_parse_xlsx')) {
    function burger_td_parse_xlsx($path, $has_headers = true, $sheet_number = 1) {
        if (!$path || !file_exists($path) || !class_exists('ZipArchive')) {
            return ['columns' => [], 'rows' => []];
        }

        $zip = new ZipArchive();
        if ($zip->open($path) !== true) return ['columns' => [], 'rows' => []];

        $shared_strings = [];
        $shared_xml = $zip->getFromName('xl/sharedStrings.xml');
        if ($shared_xml) {
            $xml = simplexml_load_string($shared_xml);
            if ($xml && isset($xml->si)) {
                foreach ($xml->si as $si) {
                    $texts = [];
                    if (isset($si->t)) {
                        $texts[] = (string) $si->t;
                    } elseif (isset($si->r)) {
                        foreach ($si->r as $r) {
                            $texts[] = (string) $r->t;
                        }
                    }
                    $shared_strings[] = implode('', $texts);
                }
            }
        }

        $sheet_number = max(1, (int) $sheet_number);
        $sheet_file = 'xl/worksheets/sheet' . $sheet_number . '.xml';
        $sheet_xml = $zip->getFromName($sheet_file);
        if (!$sheet_xml) {
            $sheet_file = 'xl/worksheets/sheet1.xml';
            $sheet_xml = $zip->getFromName($sheet_file);
        }

        $relationship_targets = [];
        $rels_file = str_replace('xl/worksheets/', 'xl/worksheets/_rels/', $sheet_file) . '.rels';
        $rels_xml = $zip->getFromName($rels_file);
        if ($rels_xml) {
            $rels = simplexml_load_string($rels_xml);
            if ($rels && isset($rels->Relationship)) {
                foreach ($rels->Relationship as $relationship) {
                    $id = (string) $relationship['Id'];
                    $target = (string) $relationship['Target'];
                    if (!$id || !$target) continue;

                    if (strpos($target, '../') === 0) {
                        $target = content_url('/uploads/') . basename($target);
                    }

                    $relationship_targets[$id] = $target;
                }
            }
        }

        $zip->close();

        if (!$sheet_xml) return ['columns' => [], 'rows' => []];

        $hyperlinks = [];
        $sheet_xml_object = simplexml_load_string($sheet_xml);
        if ($sheet_xml_object && isset($sheet_xml_object->hyperlinks->hyperlink)) {
            foreach ($sheet_xml_object->hyperlinks->hyperlink as $hyperlink) {
                $ref = (string) $hyperlink['ref'];
                if (!$ref) continue;

                $attributes = $hyperlink->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships');
                $rid = isset($attributes['id']) ? (string) $attributes['id'] : '';
                $location = (string) $hyperlink['location'];
                $url = '';

                if ($rid && !empty($relationship_targets[$rid])) {
                    $url = $relationship_targets[$rid];
                } elseif ($location) {
                    $url = '#' . ltrim($location, '#');
                }

                if (!$url) continue;

                foreach (burger_td_xlsx_cell_refs_from_range($ref) as $cell_ref) {
                    $hyperlinks[$cell_ref] = $url;
                }
            }
        }

        $xml = $sheet_xml_object ?: simplexml_load_string($sheet_xml);
        if (!$xml || empty($xml->sheetData->row)) return ['columns' => [], 'rows' => []];

        $matrix = [];
        foreach ($xml->sheetData->row as $row_xml) {
            $row = [];
            foreach ($row_xml->c as $cell) {
                $ref = (string) $cell['r'];
                preg_match('/([A-Z]+)/', $ref, $matches);
                $col_letters = $matches[1] ?? 'A';
                $col_index = 0;
                for ($i = 0; $i < strlen($col_letters); $i++) {
                    $col_index = $col_index * 26 + (ord($col_letters[$i]) - 64);
                }
                $col_index--;

                $type = (string) $cell['t'];
                $value = isset($cell->v) ? (string) $cell->v : '';

                if ($type === 's' && isset($shared_strings[(int) $value])) {
                    $value = $shared_strings[(int) $value];
                } elseif ($type === 'inlineStr' && isset($cell->is->t)) {
                    $value = (string) $cell->is->t;
                }

                $value = trim($value);

                if (!empty($hyperlinks[$ref])) {
                    $row[$col_index] = [
                        'text' => $value,
                        'url'  => $hyperlinks[$ref],
                    ];
                } else {
                    $row[$col_index] = $value;
                }
            }

            if (!empty($row)) {
                ksort($row);
                $max = max(array_keys($row));
                $normalized = [];
                for ($i = 0; $i <= $max; $i++) {
                    $normalized[] = $row[$i] ?? '';
                }
                $matrix[] = $normalized;
            }
        }

        if (empty($matrix)) return ['columns' => [], 'rows' => []];

        $headers = [];
        $first = array_shift($matrix);

        if ($has_headers) {
            foreach ($first as $header) {
                $header_text = burger_td_value_text($header);
                $key = burger_td_slug($header_text);
                $headers[$key] = $header_text;
            }
        } else {
            foreach ($first as $index => $value) {
                $key = 'columna_' . ($index + 1);
                $headers[$key] = 'Columna ' . ($index + 1);
            }
            array_unshift($matrix, $first);
        }

        $rows = [];
        $keys = array_keys($headers);
        foreach ($matrix as $data) {
            $row = [];
            foreach ($keys as $index => $key) {
                $row[$key] = $data[$index] ?? '';
            }
            $rows[] = $row;
        }

        return ['columns' => $headers, 'rows' => $rows];
    }
}

if (!function_exists('burger_td_get_manual_dataset')) {
    function burger_td_get_manual_dataset($column_config, $manual_rows) {
        $columns = [];
        foreach ((array) $column_config as $column) {
            $key = !empty($column['key']) ? burger_td_slug($column['key']) : burger_td_slug($column['nombre'] ?? '');
            if (!$key) continue;
            $columns[$key] = $column['nombre'] ?: $key;
        }

        $rows = [];
        foreach ((array) $manual_rows as $manual_row) {
            $row = [];
            foreach ((array) ($manual_row['celdas'] ?? []) as $cell) {
                $key = !empty($cell['key']) ? burger_td_slug($cell['key']) : '';
                if (!$key) continue;
                $row[$key] = $cell['valor'] ?? '';
            }
            if (!empty($row)) $rows[] = $row;
        }

        return ['columns' => $columns, 'rows' => $rows];
    }
}

if (!function_exists('burger_icon_link')) {
    function burger_icon_link() {
        return '
        <svg width="20" height="20" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-btn ms-0"> 
            <g clip-path="url(#clip0_4365_1021)"> 
                <path d="M6.875 5.3125L9.0625 7.5L6.875 9.6875" stroke="var(--white)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> 
                <path d="M7.5 13.75C10.9517 13.75 13.75 10.9517 13.75 7.5C13.75 4.04822 10.9517 1.25 7.5 1.25C4.04822 1.25 1.25 4.04822 1.25 7.5C1.25 10.9517 4.04822 13.75 7.5 13.75Z" stroke="var(--white)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> 
            </g> 
            <defs> 
                <clipPath id="clip0_4365_1021"> <rect width="15" height="15" fill="white"></rect> </clipPath> 
            </defs> 
        </svg>';
    }
}

if (!function_exists('burger_td_format_value')) {
    function burger_td_format_value($value, $type = 'texto') {
        if (is_array($value)) {
            $text = (string) ($value['text'] ?? '');
            $url  = (string) ($value['url'] ?? '');

            if ($url) {
                if (!$text) $text = $url;

                return sprintf(
                    '<a href="%s" target="_blank" rel="noopener noreferrer">%s%s</a>',
                    esc_url($url),
                    esc_html($text),
                    burger_icon_link()
                );
            }

            $value = $text;
        }

        if ($type === 'html') return wp_kses_post($value);
        if ($type === 'moneda') return esc_html($value);
        if ($type === 'numero') return esc_html($value);
        if ($type === 'fecha') return esc_html($value);
        return esc_html($value);
    }
}

$origen_datos = $origen_datos ?: 'manual';
$primera_fila_encabezados = ($primera_fila_encabezados === '' || $primera_fila_encabezados === null) ? 1 : (int) $primera_fila_encabezados;
$hoja_excel = $hoja_excel ?: 1;

if ($origen_datos === 'csv') {
    $dataset = burger_td_parse_csv(burger_td_get_file_path($archivo_dataset), (bool) $primera_fila_encabezados);
} elseif ($origen_datos === 'xlsx') {
    $dataset = burger_td_parse_xlsx(burger_td_get_file_path($archivo_dataset), (bool) $primera_fila_encabezados, (int) $hoja_excel);
} else {
    $dataset = burger_td_get_manual_dataset($columnas ?? [], $filas_manual ?? []);
}

$dataset_columns = $dataset['columns'] ?? [];
$dataset_rows = $dataset['rows'] ?? [];

$columns_to_render = [];
if (!empty($columnas)) {
    foreach ($columnas as $column) {
        $key = !empty($column['key']) ? burger_td_slug($column['key']) : burger_td_slug($column['nombre'] ?? '');
        if (!$key) continue;
        $visible = isset($column['visible']) ? (bool) $column['visible'] : true;
        if (!$visible) continue;

        $columns_to_render[] = [
            'key' => $key,
            'label' => $column['nombre'] ?: ($dataset_columns[$key] ?? $key),
            'type' => $column['tipo'] ?? 'texto',
            'class' => $column['clase_css'] ?? '',
            'orderable' => isset($column['ordenable']) ? (bool) $column['ordenable'] : true,
            'searchable' => isset($column['buscable']) ? (bool) $column['buscable'] : true,
        ];
    }
}

if (empty($columns_to_render) && !empty($dataset_columns)) {
    foreach ($dataset_columns as $key => $label) {
        $columns_to_render[] = [
            'key' => $key,
            'label' => $label,
            'type' => 'texto',
            'class' => '',
            'orderable' => true,
            'searchable' => true,
        ];
    }
}

if (empty($columns_to_render) || empty($dataset_rows)) {
    if (is_admin()) {
        echo '<div style="padding:2rem;border:1px dashed #ccc;text-align:center;">Tabla de datos: todavía no hay datos para mostrar.</div>';
    }
    return;
}

$uid = esc_attr($block_id);
$table_id = 'tabla-datos-' . $uid;
$mostrar_buscador = (int) ($mostrar_buscador !== '' ? $mostrar_buscador : 1);
$mostrar_paginacion = (int) ($mostrar_paginacion !== '' ? $mostrar_paginacion : 1);
$mostrar_info = (int) ($mostrar_info !== '' ? $mostrar_info : 1);
$filas_por_pagina = (int) ($filas_por_pagina ?: 10);
$orden_columna = $orden_columna !== '' ? (int) $orden_columna : -1;
$orden_direccion = in_array($orden_direccion, ['asc', 'desc'], true) ? $orden_direccion : 'asc';
$texto_buscar = $texto_buscar ?: 'Buscar:';
$texto_sin_resultados = $texto_sin_resultados ?: 'No se encontraron resultados.';
$boton_estilo = $boton_estilo ?: 'btn-primary';

$dt_column_defs = [];
foreach ($columns_to_render as $index => $column) {
    $dt_column_defs[] = [
        'targets' => $index,
        'orderable' => (bool) $column['orderable'],
        'searchable' => (bool) $column['searchable'],
    ];
}
?>

<style>
    .<?= esc_html($uid); ?> {
        color: #ffffff; 
        margin: <?= esc_html($section_margin); ?> !important;
        padding: <?= esc_html($section_padding); ?> !important;
        border-radius: <?= esc_html($border_radius); ?> !important;
        background-color: <?= esc_html($color_fondo); ?>;
        <?php if (!empty($imagen_fondo)) : ?>
        background-image: url('<?= esc_url($imagen_fondo); ?>');
        background-size: cover;
        background-position: center;
        <?php endif; ?>
        --td-header-bg: <?= esc_html($table_header_background ?: $color_primario); ?>;
        --td-header-color: <?= esc_html($table_header_color ?: $color_secundario); ?>;
        --td-row-hover: <?= esc_html($table_row_hover ?: 'rgba(0,0,0,.045)'); ?>;
        --td-border: <?= esc_html($table_border ?: 'rgba(0,0,0,.12)'); ?>;
        --td-striped: <?= !empty($table_striped) ? '1' : '0'; ?>;
    }
    .burger-tabla-datos .table > :not(caption) > * > * {
        background-color: transparent !important;
        border-bottom: 1px solid #ffffff40 !important;
    }
</style>

<?php $anchor_id = ! empty( $block['anchor'] ) ? sanitize_title( $block['anchor'] ) : 'tabla-datos'; ?>
<section id="<?= esc_attr( $anchor_id ); ?>" class="tabla-datos burger-tabla-datos <?= esc_attr($uid); ?>">

    <div class="<?= esc_attr($class_container); ?>">

        <div class="row justify-content-center">

            <div class="<?= esc_attr($col_container_class . ' ' . $col_md_container_class . ' ' . $col_lg_container_class); ?>">

                <?php if (!empty($titulo)) : ?>
                    <h2 class="titulo <?= esc_attr($text_align_class); ?>"><?= wp_kses_post($titulo); ?></h2>
                <?php endif; ?>

                <?php if (!empty($subtitulo)) : ?>
                    <div class="subtitulo <?= esc_attr($text_align_class); ?>"><?= esc_html($subtitulo); ?></div>
                <?php endif; ?>

                <div class="tabla-datos-wrapper">
                    <table
                        id="<?= esc_attr($table_id); ?>"
                        class="table table-bordered align-middle burger-datatable"
                        data-searching="<?= esc_attr($mostrar_buscador); ?>"
                        data-paging="<?= esc_attr($mostrar_paginacion); ?>"
                        data-info="<?= esc_attr($mostrar_info); ?>"
                        data-page-length="<?= esc_attr($filas_por_pagina); ?>"
                        data-order-column="<?= esc_attr($orden_columna); ?>"
                        data-order-direction="<?= esc_attr($orden_direccion); ?>"
                        data-empty-text="<?= esc_attr($texto_sin_resultados); ?>"
                        data-search-label="<?= esc_attr($texto_buscar); ?>"
                        data-column-defs='<?= esc_attr(wp_json_encode($dt_column_defs)); ?>'
                    >
                        <thead>
                            <tr>
                                <?php foreach ($columns_to_render as $column) : ?>
                                    <th class="<?= esc_attr($column['class']); ?>"><?= esc_html($column['label']); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataset_rows as $row) : ?>
                                <tr>
                                    <?php foreach ($columns_to_render as $column) : ?>
                                        <td class="<?= esc_attr($column['class']); ?>">
                                            <?= burger_td_format_value($row[$column['key']] ?? '', $column['type']); ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($descripcion)) : ?>
                    <div class="descripcion pt-5 <?= esc_attr($text_align_class); ?>">
                        <?= wp_kses_post($descripcion); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mostrar_boton) && !empty($boton) && count($boton) > 0 ) : ?>

                    <div class="tabla-datos-button-wrapper text-center">
                        <?php echo get_burger_button( $boton, $boton_estilo ) ?>
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>
