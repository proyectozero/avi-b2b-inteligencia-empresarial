<?php
function class_htmlDatatable($data, $opciones = []) {

    $id = generateUID();

    function color_bar($pct) {
        if ($pct >= 80) return 'bg-success';
        if ($pct >= 50) return 'bg-info';
        if ($pct >= 20) return 'bg-warning';
        return 'bg-danger';
    }

    if (empty($data)) return '<p>No hay datos disponibles.</p>';

    $config = array_merge([
        'pdf' => true,
        'excel' => true,
        'print' => true,
        'copy' => true,
        'registros' => [10, 25, 50, 100],
        'paginacion' => true,
        'busqueda' => true,
        'ordenamiento' => true,
        'titulo' => 'Tabla de datos',
    ], $opciones);

    $columnas = array_keys(reset($data));
    $cols_pct = [];
    foreach ($columnas as $col) {
        if (stripos($col, 'total') !== false || stripos($col, 'cantidad') !== false) {
            $cols_pct[] = $col;
        }
    }

    $sumas_totales = [];
    foreach ($cols_pct as $col) {
        $sum = 0;
        foreach ($data as $fila) {
            $sum += floatval($fila[$col] ?? 0);
        }
        $sumas_totales[$col] = $sum;
    }

    // Inicio de tabla
    $html = '
    <div style="overflow-x:auto;">
    <table id="'.$id.'" class="table text-light table-dark display nowrap table-striped table-bordered" style="width:100%">
    <thead><tr>';

    foreach ($columnas as $col) {
        $html .= '<th>' . htmlspecialchars($col !== null ? $col : '') . '</th>';
        if (in_array($col, $cols_pct)) {
            $html .= '<th>' . htmlspecialchars($col !== null ? $col : '') . ' (%)</th>';
        }
    }

    $html .= '</tr></thead><tbody>';

    foreach ($data as $fila) {
        $html .= '<tr>';
        foreach ($columnas as $col) {
            $valor = $fila[$col] ?? '';
            $html .= '<td>' . htmlspecialchars($valor !== null ? $valor : '') . '</td>';

            if (in_array($col, $cols_pct)) {
                $num = floatval($valor);
                $total_col = $sumas_totales[$col];
                $porcentaje = $total_col > 0 ? ($num / $total_col) * 100 : 0;
                $porcentaje_text = number_format($porcentaje, 2) . '%';

                $html .= '<td style="min-width: 50px;">
                <div class="progress" style="height: 20px; position: relative;">
                <div class="progress-bar '.color_bar($porcentaje).'" role="progressbar" style="width: '. $porcentaje .'%" aria-valuenow="'. $porcentaje .'" aria-valuemin="0" aria-valuemax="100"></div>
                <span style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); color: #fff; font-weight: bold; font-size: 0.85em;">
                '. $porcentaje_text .'
                </span>
                </div>
                </td>';
            }
        }
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div>';

    // Toast Bootstrap
    $html .= '
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
    <div id="datatableToast" class="toast text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
    <div class="toast-body">Copiado al portapapeles.</div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
    </div>
    </div>
    </div>';

    // Includes de DataTables Buttons
    $html .= '
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.copy.min.js"></script>
    ';

    // Script de inicialización
    $html .= '<script>
    function showDatatableToast() {
        const toastEl = document.getElementById("datatableToast");
        if (toastEl) {
            const bsToast = new bootstrap.Toast(toastEl);
            bsToast.show();
        }
    }

    $(document).ready(function() {

        $("#'.$id.'").DataTable({
            scrollX: true,
            dom: "Bfrtip",
            buttons: [
            '.($config['copy'] ? '{
                extend: "copy",
                text: "Copiar",
                action: function (e, dt, node, config) {
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, node, config);
                    $(".dt-button-info").hide(); 
                    showDatatableToast(); 
                }
                },' : '').'
            '.($config['excel'] ? '"excel",' : '').'
            '.($config['pdf'] ? '"pdf",' : '').'
            '.($config['print'] ? '"print"' : '').'
            ],
            paging: '.($config['paginacion'] ? 'true' : 'false').',
            pagingType: "simple",
            searching: '.($config['busqueda'] ? 'true' : 'false').',
            ordering: '.($config['ordenamiento'] ? 'true' : 'false').',
            lengthMenu: '.json_encode($config['registros']).',
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            }
            });
            });
            </script>';


            return $html;
        }
