<?php 
function class_myaviAPI($pregunta, $archivo, $debug) {

    $url = CFG_MYAVIAPI_URL;
    
    $data = [
        'pregunta' => $pregunta,
        'archivo' => $archivo,
        'temperatura' => CFG_MYAVIAPI_TEMP,
        'max_tokens' => CFG_MYAVIAPI_MAXTOKENS
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return ['error' => "Error en cURL: $error_msg"];
    }

    curl_close($ch);

    // Decodificamos JSON como array
    $response = json_decode($response, true);

    $debug_msg = null;
    if ($debug && isset($error_msg)) {
        $debug_msg = $error_msg;
    }

    $request = array(
        'pregunta'  => $pregunta,
        'archivo'   => $archivo
    );

    $respuesta = "No hay respuesta por parte del MyAVI API.";
    if (isset($response['respuesta'])) {
        $respuesta = $response['respuesta'];
    }
    
    $results = array(
        'request'   => $request,
        'response'  => $respuesta,
        'debug'     => $debug_msg
    );

    return $results;
}
