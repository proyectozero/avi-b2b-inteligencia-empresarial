<?php

// UID único para HTML IDs
function generateUID($length = 8) {
    return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $length)), 0, $length);
}

// Item del acordeón individual
function createAccordionItem($id, $title, $body, $accordion_id, $copy_target_id = null, $expanded = false) {
    $collapse_class = $expanded ? 'show' : '';
    $collapsed_class = $expanded ? '' : 'collapsed';

    $copy_button = '';
    if ($copy_target_id) {
        $copy_button = "<button class='btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2' onclick=\"copyToClipboard('$copy_target_id')\">Copiar</button>";
    }

    return <<<HTML
    <div class="accordion-item bg-dark border-secondary text-light position-relative">
        <h2 class="accordion-header" id="heading_$id">
            <button class="accordion-button bg-dark text-light border-secondary $collapsed_class" type="button" data-bs-toggle="collapse" data-bs-target="#$id" aria-expanded="$expanded" aria-controls="$id">
                $title
            </button>
        </h2>
        <div id="$id" class="accordion-collapse collapse $collapse_class" aria-labelledby="heading_$id" data-bs-parent="#accordion_$accordion_id">
            <div class="accordion-body position-relative">
                $copy_button
                $body
            </div>
        </div>
    </div>
    HTML;
}

// Convertir markdown a texto plano con saltos
function markdownToPlainTextWithNewlines($markdown) {
    $text = preg_replace('/\r\n|\r/', "\n", $markdown);
    $text = preg_replace('/\*\*|__|\*|_|`|~|#+|>|\- |\+ |!?\[.*?\]\(.*?\)/', '', $text);
    $text = html_entity_decode($text);
    return trim($text);
}

// Cuerpo HTML con efecto typewriter y markdown
function createMarkdownBodyWithTypewriter($markdown, $id, $Parsedown) {
    $htmlContent = $Parsedown->text($markdown);
    $plainText = markdownToPlainTextWithNewlines($markdown);
    return <<<HTML
    <div class="markdown-content" style="display:none;" id="html_$id">$htmlContent</div>
    <div id="$id" class="typewriter-target" data-text="{$plainText}"></div>
    HTML;
}

// Función principal
function class_htmlAccordion(
    $title = null,
    $arr_debug = null,
    $str_respuesta = null,
    $arr_datos = null,
    $arr_charts = null,
    $str_analisis = null,
    $str_recomendacion = null,
    $arr_basis = null,
    $arr_cases = null
) {
    static $toastInjected = false;
    static $typewriterInjected = false;

    $Parsedown = new Parsedown();
    $accordion_id = generateUID();
    $html = '<div class="container mt-3 mb-3">';

    if ($title) {
        $html .= '<h5>' . htmlspecialchars($title) . '</h5>';
    }

    $html .= '<div class="accordion w-100" id="accordion_' . $accordion_id . '">';

    if ($arr_debug) {
        $json_debug = json_encode($arr_debug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $json_id = 'json_debug_' . $accordion_id;
        $body = "<pre id=\"$json_id\" class=\"mb-0\" style=\"white-space: pre-wrap; word-wrap: break-word;\">$json_debug</pre>";
        $html .= createAccordionItem("debug_$accordion_id", "Debug", $body, $accordion_id, $json_id);
    }

    if ($str_respuesta) {
        $id = "respuesta_text_$accordion_id";
        $body = createMarkdownBodyWithTypewriter($str_respuesta, $id, $Parsedown);
        $html .= createAccordionItem("respuesta_$accordion_id", "Respuesta", $body, $accordion_id, $id, true);
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
        $id = "analisis_text_$accordion_id";
        $body = createMarkdownBodyWithTypewriter($str_analisis, $id, $Parsedown);
        $html .= createAccordionItem("analisis_$accordion_id", "Análisis", $body, $accordion_id, $id);
    }

    if ($str_recomendacion) {
        $id = "recomendacion_text_$accordion_id";
        $body = createMarkdownBodyWithTypewriter($str_recomendacion, $id, $Parsedown);
        $html .= createAccordionItem("recomendacion_$accordion_id", "Recomendación", $body, $accordion_id, $id);
    }

    if ($arr_basis) {
        $id = "basis_text_$accordion_id";
        $bullets = class_htmlBullets($arr_basis);
        $body = createMarkdownBodyWithTypewriter($bullets, $id, $Parsedown);
        $html .= createAccordionItem("basis_$accordion_id", "Fundamento Legal", $body, $accordion_id, $id);
    }

    if ($arr_cases) {
        $id = "cases_text_$accordion_id";
        $bullets = class_htmlBullets($arr_cases);
        $body = createMarkdownBodyWithTypewriter($bullets, $id, $Parsedown);
        $html .= createAccordionItem("cases_$accordion_id", "Jurisprudencia", $body, $accordion_id, $id);
    }

    $html .= '</div></div>'; // cierre accordion y container

    // Inyectar script de toast solo una vez
    if (!$toastInjected) {
        $toastInjected = true;
        $html .= <<<HTML
<!-- TOAST -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
  <div id="copyToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">Texto copiado al portapapeles</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
    </div>
  </div>
</div>
<script>
function copyToClipboard(elementId) {
    var el = document.getElementById(elementId);
    if (!el) return showToast("Elemento no encontrado", "bg-danger");
    var temp = document.createElement("textarea");
    temp.style.position = "fixed";
    temp.style.left = "-9999px";
    temp.value = el.innerText || el.textContent;
    document.body.appendChild(temp);
    temp.select();
    try {
        document.execCommand("copy");
        showToast("Texto copiado", "bg-success");
    } catch (err) {
        showToast("Error al copiar", "bg-danger");
    }
    document.body.removeChild(temp);
}

var toastElem = document.getElementById('copyToast');
var bsToast = new bootstrap.Toast(toastElem);

function showToast(message, bgClass = "bg-success") {
    var body = toastElem.querySelector(".toast-body");
    body.textContent = message;
    toastElem.className = "toast align-items-center text-white " + bgClass + " border-0";
    bsToast.show();
}
</script>
HTML;
    }

    // Inyectar typewriter dinámico solo una vez
    if (!$typewriterInjected) {
        $typewriterInjected = true;
        $html .= <<<HTML
<script>
const speed = 5;

function typeWriterEffect(el, text, i = 0) {
    if (i < text.length) {
        el.innerHTML += text.charAt(i) === "\\n" ? "<br>" : text.charAt(i);
        setTimeout(() => typeWriterEffect(el, text, i + 1), speed);
    } else {
        const htmlDiv = document.getElementById("html_" + el.id);
        if (htmlDiv) {
            el.style.display = "none";
            htmlDiv.style.display = "block";
        }
    }
}

function applyTypewriterListeners(scope = document) {
    scope.querySelectorAll(".accordion-collapse").forEach(acc => {
        const handler = () => {
            const target = acc.querySelector(".typewriter-target");
            if (target && !target.dataset.typed) {
                target.dataset.typed = "true";
                target.innerHTML = '';
                typeWriterEffect(target, target.dataset.text);
            }
        };
        acc.removeEventListener("shown.bs.collapse", handler);
        acc.addEventListener("shown.bs.collapse", handler);
        if (acc.classList.contains("show")) handler();
    });
}

applyTypewriterListeners();

const observer = new MutationObserver(mutations => {
    for (const m of mutations) {
        m.addedNodes.forEach(node => {
            if (node.nodeType === 1 && node.matches(".accordion-collapse, .accordion-collapse *")) {
                applyTypewriterListeners(node.closest(".accordion"));
            }
        });
    }
});
observer.observe(document.body, { childList: true, subtree: true });
</script>
HTML;
    }

    return $html;
}
?>
