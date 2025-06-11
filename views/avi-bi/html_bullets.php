<?php 
function class_htmlBullets($arr_data) {
    $html = '<ul>';
    
    foreach ($arr_data as $key => $value) {
        if (is_array($value)) {
            // Si el array está vacío, no mostrar nada
            if (empty($value)) {
                continue;
            }
            // Mostrar clave y anidar
            $subHtml = class_htmlBullets($value);
            if ($subHtml !== '<ul></ul>') {
                $html .= '<li><b>' . htmlspecialchars($key) . '</b>: ' . $subHtml . '</li>';
            }
        } elseif (trim((string)$value) !== '') {
            // Mostrar solo si el valor no está vacío
            $html .= '<li><b>' . htmlspecialchars($key) . '</b>: ' . htmlspecialchars($value) . '</li>';
        }
    }

    $html .= '</ul>';
    return $html;
}
