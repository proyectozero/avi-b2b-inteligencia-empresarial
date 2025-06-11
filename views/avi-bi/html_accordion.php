<?php 
function class_htmlAccordion(
    $title              = null, 
    $arr_debug          = null, 
    $str_respuesta      = null, 
    $arr_datos          = null, 
    $arr_charts         = null, 
    $str_analisis       = null, 
    $str_recomendacion  = null, 
    $arr_basis          = null, 
    $arr_cases          = null
) {
    static $toastInjected = false; // controla la inyección única del toast y script
    $Parsedown = new Parsedown();

    // Genera un UID para ids únicos
    function generateUID($length = 8) {
        return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $length)), 0, $length);
    }

    $accordion_id = generateUID();
    $html = '<div class="container mt-3 mb-3">';

    if ($title) {
        $html .= '<h5>' . htmlspecialchars($title) . '</h5>';
    }

    $html .= '<div class="accordion accordion-flush w-100" id="accordion_' . $accordion_id . '">';

    // Función auxiliar para crear ítems del acordeón
    function createAccordionItem($id, $title, $body, $accordion_id, $copy_target_id = null, $expanded = false) {
        $collapse_class = $expanded ? 'show' : '';
        $collapsed_class = $expanded ? '' : 'collapsed';

        $copy_button = '';
        if ($copy_target_id) {
            $copy_button = "
            <div class='d-flex justify-content-end gap-2 mt-2 mb-2'>
            <button 
            class='btn btn-sm btn-outline-secondary text-white' 
            onclick=\"printElement('$copy_target_id')\">
            <i class='bi bi-printer'></i> Imprimir
            </button>            
            <button 
            class='btn btn-sm btn-outline-secondary text-white' 
            onclick=\"copyToClipboard('$copy_target_id')\">
            <i class='bi bi-clipboard'></i> Copiar
            </button>
            </div>";
        }




        return <<<HTML
        <div class="accordion-item border-secondary text-light position-relative" style="background-color: transparent;">
            <h2 class="accordion-header" id="heading_$id">
                <button class="accordion-button text-light border-secondary $collapsed_class" type="button" data-bs-toggle="collapse" data-bs-target="#$id" aria-expanded="$expanded" aria-controls="$id" style="background-color: transparent;">
                    $title
                </button>
            </h2>
            <div id="$id" class="accordion-collapse collapse border-secondary $collapse_class" aria-labelledby="heading_$id" data-bs-parent="#accordion_$accordion_id">
                <div class="accordion-body position-relative px-1" style="padding-top: 2.5rem;">
                    $body
                    $copy_button
                </div>
            </div>
        </div>
        HTML;
    }


    // Agregar cada sección si existen datos
    if ($arr_debug) {
        $json_debug = json_encode($arr_debug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $json_id = 'json_debug_' . $accordion_id;
        $bullets_debug = "<pre id=\"$json_id\" class=\"mb-0\" style=\"white-space: pre-wrap; word-wrap: break-word;\">$json_debug</pre>";
        $html .= createAccordionItem("debug_$accordion_id", "Debug", $bullets_debug, $accordion_id, $json_id);
    }

    if ($str_respuesta) {
        $respuesta_id = "respuesta_text_$accordion_id";
        $html .= createAccordionItem("respuesta_$accordion_id", "Respuesta", "<div id=\"$respuesta_id\">".$Parsedown->text($str_respuesta)."</div>", $accordion_id, $respuesta_id, true);
    }

    if ($arr_datos) {
        $datatable = class_htmlDatatable($arr_datos, ['titulo' => 'datatable_datos', 'responsive' => false]);
        $html .= createAccordionItem("datos_$accordion_id", "Datos", $datatable, $accordion_id);
    }

    if ($arr_charts) {
        $chart = class_htmlGoogleCharts($arr_charts['title'], $arr_charts['type'], $arr_charts['3d'], $arr_charts['x_data'], $arr_charts['y_data']);
        $html .= createAccordionItem("charts_$accordion_id", "Gráficas", $chart, $accordion_id, null, true);
    }

    if ($str_analisis) {
        $analisis_id = "analisis_text_$accordion_id";
        $html .= createAccordionItem("analisis_$accordion_id", "Análisis", "<div id=\"$analisis_id\">".$Parsedown->text($str_analisis)."</div>", $accordion_id, $analisis_id);
    }

    if ($str_recomendacion) {
        $recomendacion_id = "recomendacion_text_$accordion_id";
        $html .= createAccordionItem("recomendacion_$accordion_id", "Recomendación", "<div id=\"$recomendacion_id\">".$Parsedown->text($str_recomendacion)."</div>", $accordion_id, $recomendacion_id);
    }

    if ($arr_basis) {
        $basis_id = "basis_text_$accordion_id";
        $bullets = class_htmlBullets($arr_basis);
        $html .= createAccordionItem("basis_$accordion_id", "Fundamento Legal", "<div id=\"$basis_id\">".$Parsedown->text($bullets)."</div>", $accordion_id, $basis_id);
    }

    if ($arr_cases) {
        $cases_id = "cases_text_$accordion_id";
        $bullets = class_htmlBullets($arr_cases);
        $html .= createAccordionItem("cases_$accordion_id", "Jurisprudencia", "<div id=\"$cases_id\">".$Parsedown->text($bullets)."</div>", $accordion_id, $cases_id);
    }

    $html .= '</div></div>'; // cierre accordion y container

    // Solo inyectar el Toast container y el script una vez
    if (!$toastInjected) {
        $toastInjected = true;

        $html .= <<<HTML
        <!-- Toast container Bootstrap -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
          <div id="copyToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
              <div class="toast-body">
                Texto copiado al portapapeles
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
        </div>
    </div>
</div>
<script>
    function copyToClipboard(elementId) {
        var el = document.getElementById(elementId);
        if (!el) {
            showToast("Elemento a copiar no encontrado.", "bg-danger");
            return;
        }

        var temp = document.createElement("textarea");
        temp.style.position = "fixed";
        temp.style.left = "-9999px";
        temp.value = el.innerText || el.textContent;
        document.body.appendChild(temp);
        temp.select();
        try {
            var successful = document.execCommand("copy");
            if(successful){
                showToast("Texto copiado al portapapeles", "bg-success");
            } else {
                showToast("No se pudo copiar el texto", "bg-warning");
            }
        } catch (err) {
            showToast("Error al copiar: " + err, "bg-danger");
        }
        document.body.removeChild(temp);
    }

    var toastElem = document.getElementById('copyToast');
    var bsToast = new bootstrap.Toast(toastElem);

    function showToast(message, bgClass = "bg-success") {
        var toastBody = toastElem.querySelector(".toast-body");
        toastBody.textContent = message;

        toastElem.classList.remove("bg-success", "bg-danger", "bg-warning");
        toastElem.classList.add(bgClass);

        bsToast.show();
    }

    function printElement(elementId) {
        var el = document.getElementById(elementId);
        if (!el) {
            showToast("Elemento a imprimir no encontrado.", "bg-danger");
            return;
        }
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Imprimir</title>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(el.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }


</script>
HTML;
}

return $html;
}
?>
