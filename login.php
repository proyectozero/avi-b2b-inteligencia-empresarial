<?php
session_start();
require_once('config.php');

$action = $_GET['action'] ?? null;

if ($action === "google") {
    $client_id = CFG_SSO_GOOGLE_CLIENTID;
    $redirect_uri = CFG_APP_URL . "/callback.php";
    $scope = 'email profile';

    $permanent = isset($_GET['permanent']) ? '&permanent=1' : '';

    $url = "https://accounts.google.com/o/oauth2/v2/auth?"
        . "client_id={$client_id}"
        . "&redirect_uri={$redirect_uri}{$permanent}"
        . "&response_type=code"
        . "&scope=" . urlencode($scope)
        . "&access_type=online"
        . "&prompt=select_account";

    header('Location: ' . $url);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Favicon y PWA -->
    <link rel="icon" href="<?php echo CFG_APP_URL; ?>/assets/favicon/favicon.ico" />
    <link rel="manifest" href="<?php echo CFG_APP_URL; ?>/assets/pwa/manifest.json" />

    <title><?php echo $cfg_sitetitle; ?> | Iniciar sesión</title>
    <link rel="stylesheet" href="<?php echo CFG_APP_URL; ?>/assets/css/style.css?v=1.2.53" />
    <link rel="stylesheet" href="assets/css/login.css?v=1.0.3" />
</head>
<body>
    <?php require_once('preloader.php'); ?>
    <div class="login-container">
        <div class="login-box">
            <img src="assets/images/logos/logo_avib2b_white.png" alt="Logo" class="logo" />
            <h2>Iniciar sesión</h2>

            <form id="google-login-form">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="permanentSession">
                    <label class="form-check-label" for="permanentSession">Mantener sesión iniciada</label>
                </div>

                <button type="submit" class="btn google-btn w-100 d-flex align-items-center justify-content-center gap-2">
                    <img src="assets/images/login/Google_Icons-09-512.webp" alt="Google" class="google-icon" style="width: 20px;" />
                    <span>Iniciar con Google</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("google-login-form").addEventListener("submit", function (e) {
            e.preventDefault();
            const isPermanent = document.getElementById("permanentSession").checked;
            let url = "<?php echo CFG_APP_URL; ?>/login.php?action=google";
            if (isPermanent) {
                url += "&permanent=1";
            }
            window.location.href = url;
        });
    </script>

    <script type="module" src="<?php echo CFG_APP_URL; ?>/assets/js/login.js?v=1.0.8"></script>
</body>
</html>
