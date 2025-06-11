<?php
// functions.php

function generarSQLConOpenAI($pregunta) {
    $prompt = "Convertí la siguiente pregunta en una consulta SQL para MySQL. Esquema:\n\n".
              "Tabla: dw_ventas\n".
              "Campos: id_venta, fecha_venta, cliente_id, cliente_nombre, producto_id, producto_nombre, cantidad, precio_unitario, monto_total, canal_venta, estado_venta, region\n\n".
              "Pregunta: \"$pregunta\"\n".
              "Respuesta:";

    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            ["role" => "system", "content" => "Sos un asistente que genera consultas SQL para MySQL."],
            ["role" => "user", "content" => $prompt]
        ],
        "temperature" => 600,
        "max_tokens" => 150
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENAI_API_KEY
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return "Error en cURL: " . curl_error($ch);
    }
    curl_close($ch);

    $result = json_decode($response, true);
    if (isset($result['choices'][0]['message']['content'])) {
        $rawContent = trim($result['choices'][0]['message']['content']);

        // Limpiar el bloque ```sql ... ```
        $cleaned = preg_replace('/^```sql\s*([\s\S]*?)\s*```$/', '$1', $rawContent);

        return trim($cleaned);
    } else {
        return "No se pudo generar la consulta SQL.";
    }
}
?>
