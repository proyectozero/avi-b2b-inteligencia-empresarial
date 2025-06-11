<?php
require_once('config.php');
require_once('conn.php');

//users class
require_once('class/users/auth.php');
require_once('class/users/logout.php');
require_once('class/users/usersinfo.php');

//restrict
class_usersAuth();

$UsersId = null;
if (isset($_GET['UsersId'])) {
  $UsersId = $_GET['UsersId'];
}

// recordset - users info
$usersinfo      = class_usersInfo($UsersId, $conn);
$row_usersinfo  = $usersinfo['info'];
$row_customers  = $usersinfo['info'];

$customers_name = $row_customers['Name'] ?? 'Sin empresa';

$UsersId = null;
if (isset($row_usersinfo['UsersId'])) {
  $UsersId = $row_usersinfo['UsersId'];
}

$CustomersId = null;
if (isset($row_usersinfo['CustomersId'])) {
  $CustomersId = $row_usersinfo['CustomersId'];
}
header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title><?php echo $cfg_sitetitle; ?> | <?php echo $cfg_sitedescription; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?php echo CFG_APP_URL; ?>/assets/favicon/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo CFG_APP_URL; ?>/assets/favicon/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo CFG_APP_URL; ?>/assets/favicon/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo CFG_APP_URL; ?>/assets/favicon/favicon-16x16.png" />
  <link rel="manifest" href="<?php echo CFG_APP_URL; ?>/assets/pwa/manifest.json" />

  <!-- pwa -->
  <meta name="theme-color" content="#000" />
  <link rel="apple-touch-icon" href="<?php echo CFG_APP_URL; ?>/assets/icons/icon-192.png" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: '#000000',
            secondary: '#ffffff'
          }
        }
      }
    }
  </script>

  <!-- Bootstrap / Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- system styles -->
  <link rel="stylesheet" href="<?php echo CFG_APP_URL; ?>/assets/css/style.css?v=1.2.59" />
  <link rel="stylesheet" href="<?php echo CFG_APP_URL; ?>/assets/css/admin.css?v=1.0.6" />

  <!-- Dark mode script -->
  <script>
    // Check for dark mode preference
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  </script>
  
  <!-- Scripts -->
  <script src="<?php echo CFG_APP_URL; ?>/assets/js/darkmode.js" defer></script>
</head>
<body class="chat-body dark:bg-gray-900 dark:text-white transition-colors duration-200">

  <?php require_once('preloader.php'); ?>


  <!-- Header con logo y menú hamburguesa -->
  <header class="top-bar d-flex align-items-center justify-content-between px-3 py-2 dark:bg-gray-800">
    <!-- Icono nuevo mensaje: izquierda en desktop, derecha en móvil -->
    <a href="<?php echo CFG_APP_URL; ?>/" class="d-flex align-items-center text-white text-decoration-none order-md-0 order-2" aria-label="Nuevo mensaje" style="font-size: 1.2rem;">
      <i class="fas fa-arrow-up-right-from-square"></i>
    </a>

    <!-- Logo centrado -->
    <div class="mx-auto order-1">
      <img src="<?php echo CFG_APP_URL; ?>/assets/images/logos/logo_avib2b_white.png" alt="Logo AVI" class="logo" loading="eager" style="height: 40px;" />
    </div>

    <!-- Perfil a la derecha en desktop, oculto en móvil -->
    <div class="d-none d-md-block order-md-2 order-1 flex items-center">
      <!-- Dark mode toggle -->
      <button id="darkModeToggle" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
        <i class="fas fa-moon dark:hidden"></i>
        <i class="fas fa-sun hidden dark:inline"></i>
      </button>
      
      <div class="profile-icon position-relative" id="profileIcon" role="button" aria-label="Perfil de usuario" tabindex="0" style="cursor:pointer;">
        <img src="<?php echo $row_usersinfo['Picture']; ?>" alt="Perfil" class="profile-img rounded-circle" style="height:40px; width:40px; object-fit: cover;" />
      </div>
    </div>

    <!-- Menú hamburguesa visible solo en móvil -->
    <div class="d-md-none order-0">
      <div id="menuToggle" role="button" aria-label="Abrir menú" tabindex="0" style="cursor:pointer;">
        <span class="bar d-block mb-1" style="width: 25px; height: 3px; background: white;"></span>
        <span class="bar d-block mb-1" style="width: 25px; height: 3px; background: white;"></span>
        <span class="bar d-block" style="width: 25px; height: 3px; background: white;"></span>
      </div>
    </div>
  </header>
  <!-- Al final del body -->
  <script src="https://www.gstatic.com/charts/loader.js" defer></script>
<!-- Menú desplegable para perfil en desktop -->
<nav class="profile-dropdown d-none position-absolute bg-white dark:bg-gray-800 shadow rounded" id="profileDropdown" style="top: 50px; right: 0; width: 220px; z-index: 1050;">
  <div class="p-3 border-bottom dark:border-gray-700">
    <div class="d-flex align-items-center">
      <img src="<?php echo $row_usersinfo['Picture']; ?>" alt="Foto perfil" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
      <div class="text-dark dark:text-white ms-2">
        <strong><?php echo htmlspecialchars($row_usersinfo['FullName']); ?></strong><br>
        <small style="opacity:0.7;"><?php echo htmlspecialchars($row_usersinfo['Email']); ?></small>
      </div>
    </div>
  </div>
  <ul class="list-unstyled m-0 p-2 dark:text-gray-200">

    <!-- debug switch - desktop -->
    <li class="dropdown-item px-2">
      <div class="d-flex justify-content-between align-items-center">
        <label class="form-check-label small mb-0 text-dark dark:text-gray-200" for="debugSwitchDesktop">Debug</label>
        <div class="form-check form-switch m-0">
          <input class="form-check-input" type="checkbox" id="debugSwitchDesktop">
        </div>
      </div>
    </li>

    <!-- menu -->
    <li><a class="dropdown-item flex items-center gap-2" href="<?php echo CFG_APP_URL; ?>/admin/dashboard.php">
        <i class="fas fa-tachometer-alt text-gray-600 dark:text-gray-400"></i>
        Dashboard
    </a></li>
    <li><a class="dropdown-item" href="<?php echo CFG_APP_URL; ?>/">Nueva conversación</a></li>
    <li><a class="dropdown-item" href="<?php echo CFG_APP_URL; ?>/admin/myavi.php">MyAVI</a></li>
    <li>
      <a class="dropdown-item" data-bs-toggle="collapse" href="#adminEmpresaMenuDropdown" role="button" aria-expanded="false" aria-controls="adminEmpresaMenuDropdown">
        Administrar Empresa <i class="fas fa-chevron-down float-end"></i>
      </a>
      <div class="collapse ps-3" id="adminEmpresaMenuDropdown">
        <ul class="list-unstyled">
          <li><a class="dropdown-item" href="<?php echo CFG_APP_URL; ?>/admin/employees.php">Empleados</a></li>
          <li><a class="dropdown-item" href="<?php echo CFG_APP_URL; ?>/admin/connectors.php">Conectores</a></li>
        </ul>
      </div>
    </li>
    <li><a class="dropdown-item" href="<?php echo CFG_APP_URL; ?>/logout.php">Cerrar sesión</a></li>
  </ul>
</nav>


<!-- Menú lateral deslizable -->
<nav class="side-nav dark:bg-gray-800" id="sideNav">
  <div class="side-nav-header dark:bg-gray-900">
    <div class="d-flex justify-content-start">
      <button id="closeMenu" class="btn btn-link text-white text-decoration-none position-absolute top-0 start-0 m-2 fs-3" aria-label="Cerrar menú">&times;</button>
    </div>
    <div class="profile-picture-container">
      <img src="<?php echo $row_usersinfo['Picture']; ?>" alt="Foto de perfil" class="profile-picture" referrerpolicy="no-referrer">
      <p class="pt-3"><?php echo htmlspecialchars($row_usersinfo['FullName']); ?></p>
      <p class="mb-0 small" style="font-size: 0.7rem; opacity: 0.6;"><?php echo htmlspecialchars($row_usersinfo['Email']); ?></p>
      <?php if($customers_name != 'Sin empresa'){ ?>
        <form method="post" id="changeCustomerForm" class="mb-0 small" style="font-size: 0.7rem; opacity: 0.8;">
          <label for="selectCustomer" class="form-label">Empresa:</label>
          <select name="change_customer_id" id="selectCustomer" class="form-select form-select-sm" onchange="document.getElementById('changeCustomerForm').submit();">
            <?php foreach ($customers as $c): ?>
              <option value="<?php echo $customers['CustomersId']; ?>" <?php echo ($customers['Default'] == 1) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($customers['Name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </form>
      <?php } else {
        echo "Sin Empresa";
      } ?>
    </div>
  </div>
  <ul class="menu-list">

    <!-- debug switch - mobile -->
    <li class="">
      <div class="d-flex justify-content-between align-items-center">
        <label class="form-check-label small mb-0 text-light" for="debugSwitchMobile">Debug</label>
        <div class="form-check form-switch m-0">
          <input class="form-check-input" type="checkbox" id="debugSwitchMobile">
        </div>
      </div>
    </li>


    <li><a href="<?php echo CFG_APP_URL; ?>/admin/dashboard.php" class="flex items-center gap-2">
        <i class="fas fa-tachometer-alt"></i>
        Dashboard
    </a></li>
    <li><a href="<?php echo CFG_APP_URL; ?>/">Nueva conversación</a></li>
    <li><a href="<?php echo CFG_APP_URL; ?>/admin/myavi.php">MyAVI</a></li>
    <!-- Administrar Empresa con submenú colapsable -->
    <li>
      <a class="d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#adminEmpresaMenu" role="button" aria-expanded="false" aria-controls="adminEmpresaMenu">
        Administrar Empresa
        <i class="fas fa-chevron-down"></i>
      </a>
      <div class="collapse ps-3" id="adminEmpresaMenu">
        <ul class="list-unstyled">
          <li><a href="<?php echo CFG_APP_URL; ?>/admin/employees.php">Empleados</a></li>
          <li><a href="<?php echo CFG_APP_URL; ?>/admin/connectors.php">Conectores</a></li>
        </ul>
      </div>
    </li>
    <li><a href="<?php echo CFG_APP_URL; ?>/logout.php">Cerrar sesión</a></li>
  </ul>
</nav>