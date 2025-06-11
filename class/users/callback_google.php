<?php 
function class_usersCallbackGoogle(string $code, mysqli $conn, bool $permanente = false, int $dias = 30): bool {

    // Configurar sesión (opcionalmente permanente)
    if ($permanente) {
        $lifetime = 60 * 60 * 24 * $dias;
        ini_set('session.gc_maxlifetime', $lifetime);
        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    session_start();

    $client_id     = CFG_SSO_GOOGLE_CLIENTID;
    $client_secret = CFG_SSO_GOOGLE_CLIENTSECRET;
    $redirect_uri  = CFG_APP_URL . "/callback.php";

    // Obtener token
    $token_url = 'https://oauth2.googleapis.com/token';
    $data = [
        'code' => $code,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) return false;

    $token_info = json_decode($response, true);
    if (!isset($token_info['access_token'])) return false;

    $access_token = $token_info['access_token'];

    // Obtener datos del usuario
    $user_info_raw = file_get_contents("https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token={$access_token}");
    $user = json_decode($user_info_raw, true);

    // Validar datos
    $user_email     = $conn->real_escape_string($user['email'] ?? '');
    $user_fullname  = $conn->real_escape_string($user['name'] ?? '');
    $user_firstname = $conn->real_escape_string($user['given_name'] ?? '');
    $user_lastname  = $conn->real_escape_string($user['family_name'] ?? '');
    $user_picture   = $conn->real_escape_string($user['picture'] ?? '');
    $sso_method     = "google";
    $sso_id         = $conn->real_escape_string($user['id'] ?? '');

    if (empty($user_email)) return false;

    // Buscar o insertar usuario
    $sql_check = "SELECT UsersId FROM users WHERE Email = '$user_email'";
    $result = $conn->query($sql_check);

    if ($result->num_rows == 0) {
        $sql_insert = "INSERT INTO users (Email, FullName, FirstName, LastName, Picture, SSOMethod, SSOId, Status) 
                       VALUES ('$user_email', '$user_fullname', '$user_firstname', '$user_lastname', '$user_picture', '$sso_method', '$sso_id', 1)";
        if (!$conn->query($sql_insert)) return false;
    }

    // Obtener ID del usuario
    $result = $conn->query("SELECT UsersId FROM users WHERE Email = '$user_email'");
    $row = $result->fetch_assoc();
    if (!$row) return false;

    $_SESSION['user_id'] = $row['UsersId'];
    return true;
}