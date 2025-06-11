<?php 
function class_aviLegalAPI($query, $matches, $idCnxFilter, $debug = 0) {
    $url = CFG_AVILEGALAPI_URL;

    $headers = [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: en-US,en;q=0.9,es;q=0.8',
        'Content-Type: application/json',
        'Origin: https://avi-legal.com',
        'Priority: u=1, i',
        'Referer: https://avi-legal.com/?q=eyJhIjoiMTIiLCJiIjoib3NpYW5uY3JAZ21haWwuY29tIiwiYyI6MTA1Nzk4MDgsInBtIjoiNTEifQ==',
        'Sec-CH-UA: "Chromium";v="136", "Google Chrome";v="136", "Not.A/Brand";v="99"',
        'Sec-CH-UA-Mobile: ?0',
        'Sec-CH-UA-Platform: "Windows"',
        'Sec-Fetch-Dest: empty',
        'Sec-Fetch-Mode: cors',
        'Sec-Fetch-Site: same-origin',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36',
    ];

    $data = json_encode([
        'query' => $query,
        'matches' => $matches,
        'id_cnx_filter' => $idCnxFilter
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_POST, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return ['error' => true, 'message' => $error_msg];
    }

    curl_close($ch);

    $response = json_decode($response, true); // Retorna array asociativo

    $request = array(
        'query'  		=> $query,
        'matches'  		=> $matches,
        'idCnxFilter'   => $idCnxFilter,
        'debug'   		=> $debug,
    );

    $debug_msg = null;
    if ($debug && isset($error_msg)) {
        $debug_msg = $error_msg;
    }

    $results = array(
        'request'   => $request,
        'response'  => $response,
        'debug'     => $debug_msg
    );

    return $results;
}

