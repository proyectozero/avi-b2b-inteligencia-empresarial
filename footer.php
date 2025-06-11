<?php require_once('install.php'); ?>

<!-- Switch Debug -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const debugDesktop = document.getElementById('debugSwitchDesktop');
  const debugMobile = document.getElementById('debugSwitchMobile');
  const initialDebugValue = <?php echo $row_usersinfo['Debug'] ? 'true' : 'false'; ?>;

  // Set initial values
  if (debugDesktop) debugDesktop.checked = initialDebugValue;
  if (debugMobile) debugMobile.checked = initialDebugValue;

  function syncAndUpdate(sourceSwitch) {
    const debugValue = sourceSwitch.checked ? 1 : 0;

    // Sync both switches
    if (debugDesktop && sourceSwitch !== debugDesktop) debugDesktop.checked = sourceSwitch.checked;
    if (debugMobile && sourceSwitch !== debugMobile) debugMobile.checked = sourceSwitch.checked;

    // Update via fetch
    fetch('update_debug.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'debug=' + debugValue
    })
    .then(response => response.text())
    .then(data => console.log("Debug actualizado:", data))
    .catch(error => console.error('Error al actualizar Debug:', error));
  }

  // Listeners
  if (debugDesktop) debugDesktop.addEventListener('change', () => syncAndUpdate(debugDesktop));
  if (debugMobile) debugMobile.addEventListener('change', () => syncAndUpdate(debugMobile));
});
</script>

<!-- menu de profile cuando es desktop -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const profileIcon = document.getElementById('profileIcon');
    const profileDropdown = document.getElementById('profileDropdown');

    if (profileIcon && profileDropdown) {
      profileIcon.addEventListener('click', (e) => {
      e.stopPropagation(); // para que el clic no cierre el menú inmediatamente
      profileDropdown.classList.toggle('d-none');
    });

    // Cierra el menú si haces clic fuera
      document.addEventListener('click', (e) => {
        if (!profileDropdown.contains(e.target) && !profileIcon.contains(e.target)) {
          profileDropdown.classList.add('d-none');
        }
      });
    }
  });
</script>

<!-- typewrite fx -->
<script>
  // Función que escribe el texto carácter a carácter
  function typeWriterEffect(container, text, speed, index = 0, callback = null) {
    if (index < text.length) {
      container.innerHTML += text.charAt(index);
      setTimeout(() => typeWriterEffect(container, text, speed, index + 1, callback), speed);
    } else if (callback) {
      callback();
    }
  }

  // Activa el efecto para un contenedor específico
  function activateTypewriter(container) {
    if (!container) return;
    if (container.getAttribute('data-typed') === 'true') return;

    // Guardar el HTML original la primera vez
    if (!container.hasAttribute('data-original-html')) {
      container.setAttribute('data-original-html', container.innerHTML);
    }

    // Usar solo el texto plano para la animación
    const text = container.textContent.trim();
    const speedAttr = container.getAttribute('data-speed');
    const speed = speedAttr ? parseInt(speedAttr) : 50;

    container.innerHTML = ''; // Vaciar para animar
    container.setAttribute('data-typed', 'true');

    typeWriterEffect(container, text, speed, 0, () => {
      // Restaurar el HTML original cuando termina
      container.innerHTML = container.getAttribute('data-original-html');
    });
  }

  // Al cargar la página, activar el efecto usando IntersectionObserver si está disponible
  document.addEventListener('DOMContentLoaded', () => {
    const targets = document.querySelectorAll('.typewriter-target');

    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            activateTypewriter(entry.target);
            observer.unobserve(entry.target);
          }
        });
      }, {threshold: 0.1});

      targets.forEach(target => {
        observer.observe(target);
      });

    } else {
      // Si no soporta IntersectionObserver, activar todo inmediatamente
      targets.forEach(target => {
        activateTypewriter(target);
      });
    }
  });
</script>

<!-- UI js -->
<script type="module" src="<?php echo CFG_APP_URL; ?>/assets/js/menu.js?v=1.0.11"></script>
<script type="module" src="<?php echo CFG_APP_URL; ?>/assets/js/pwa.js?v=1.0.11"></script>

<!-- libs -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>