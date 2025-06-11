<?php require_once('header.php'); ?>

<!-- Incluí los scripts y CSS de DataTables en la página principal -->
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- datatables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- app -->
<link rel="stylesheet" href="<?php echo CFG_APP_URL; ?>/assets/css/chat.css?v=1.0.55" />
<link rel="stylesheet" href="<?php echo CFG_APP_URL; ?>/assets/css/datatables.css?v=1.0.1" />
<link rel="stylesheet" href="<?php echo CFG_APP_URL; ?>/assets/css/charts.css?v=1.0.1" />

<!-- charts.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js?v=1.0.1"></script>

<?php
$emojiOptions = [
  ['icon' => 'fa-face-smile', 'label' => 'Recomienda'],
  ['icon' => 'fa-face-meh', 'label' => 'Neutral'],
  ['icon' => 'fa-face-frown', 'label' => 'Pésimista'],
  ['icon' => 'fa-face-angry', 'label' => 'Agresivo'],
  ['icon' => 'fa-face-laugh-beam', 'label' => 'Soluciona'],
  ['icon' => 'fa-chart-line', 'label' => 'Analítico'],
  ['icon' => 'fa-glasses', 'label' => 'Minucioso'],
  ['icon' => 'fa-person-running', 'label' => 'Breve']
];

$quickPrompts = [
  'Quiero un reporte',
  'Top 5 de Ventas',
  'Resumen de Conversación',
  'Receta de Cocina',
  'Consulta Legal',
  'MyAvi, hablame de...',
];
?>

<div id="chat-window" class="chat-window">

  <div id="initialMessage" class="initial-message typewriter-target" data-speed="20">
    ¿Con qué puedo ayudarte?
  </div>



  <div id="quickPrompts" class="quick-prompts d-flex flex-wrap">
    <?php foreach ($quickPrompts as $prompt): ?>
      <span
      class="badge bg-primary text-white m-1 px-3 py-2 prompt-badge fs-6"

      style="cursor: pointer;"
      onclick="sendQuickPrompt('<?= htmlspecialchars($prompt, ENT_QUOTES) ?>')"
      >
      <?= htmlspecialchars($prompt) ?>
    </span>
  <?php endforeach; ?>
</div>


<!-- Aquí puedes agregar los mensajes que se renderizan -->
<div id="chatContent" class="chat-content">
  <!-- Mensajes dinámicos se insertan aquí -->
</div>
</div>


<!-- El área de input va justo al final del chat-container -->
<div class="input-area">
  <input type="hidden" id="personality" value="">

  <input type="hidden" id="debug" value="<?= $row_usersinfo['Debug'] ?? 0 ?>"> <!-- Ajusta con tu variable PHP -->

  <textarea id="userInput" class="form-control text-box-1" placeholder="Solicita lo que quieras..." rows="1"
  oninput="autoGrow(this)" onkeydown="if(event.key==='Enter' && !event.shiftKey) { event.preventDefault(); sendMessage(); }"></textarea>

  <div class="input-area-buttons d-flex justify-content-between align-items-center">

    <!-- Botón de pegar (solo visible si hay texto en portapapeles) -->
    <button id="pasteButton" class="icon-button d-none" onclick="pasteFromClipboard()" title="Pegar">
      <i class="fas fa-clipboard"></i>
    </button>

    <div class="emoji-selector me-auto">
    <!--
    <button class="icon-button" onclick="toggleEmojiMenu()" title="Seleccionar carita">
      <i id="selectedEmoji" class="fa-solid fa-face-smile"></i>
    </button>
    -->

      <div id="emojiMenu" class="emoji-menu d-none">
        <?php foreach ($emojiOptions as $emoji): ?>
          <div class="emoji-item" onclick="selectEmojiIcon('<?= $emoji['icon'] ?>')">
            <i class="fa-solid <?= $emoji['icon'] ?>"></i><span><?= $emoji['label'] ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>


    <div class="d-flex align-items-center">
      <!-- Nuevo botón de grabación -->
<!--       <button id="recordButton" class="icon-button me-2" onclick="toggleRecording()" title="Grabar audio">
        <i class="fas fa-headphones" id="recordIcon"></i>
      </button> -->

      <!-- Botón de enviar (igual que antes) -->
      <button id="sendButton" class="icon-button" onclick="toggleSendStop()" title="Enviar">
        <i class="fas fa-arrow-up" id="sendIcon"></i>
      </button>

      <!-- Botón de voz original (NO tocar) -->
      <button class="icon-button" onclick="startVoiceInput()" title="Hablar" id="micButton">
        <i class="fas fa-microphone" id="micIcon"></i>
      </button>
    </div>
  </div>
</div>
</div>

<!-- Modal para descripción de grabación -->
<div id="recordingModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
  <div style="background:#fff; padding:20px; border-radius:8px; max-width:400px; width:90%; color: #000;">
    <h3>Ingrese descripción de la grabación</h3>
    <input type="text" id="recordingDescription" placeholder="Ej: Reunión con José" style="width:100%; padding:8px; margin:12px 0;"/>
    <div style="text-align:right;">
      <button class="btn btn-secondary" onclick="cancelRecordingModal()" style="margin-right:10px;">Cancelar</button>
      <button class="btn btn-secondary" onclick="startRecordingWithDescription()">Grabar</button>
    </div>
  </div>
</div>

<!-- Modal de confirmación después de guardar grabación -->
<div id="savedRecordingModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
  <div style="background:#fff; padding:20px; border-radius:8px; max-width:400px; width:90%; color: #000;">
    <h3>Grabación guardada</h3>
    <p>¿Desea ir al centro de grabaciones o continuar en el chat?</p>
    <div style="text-align:right;">
      <button class="btn btn-secondary" onclick="hideSavedRecordingModal()">Continuar en el chat</button>
      <a class="btn btn-secondary" href="grabaciones.php" class="btn btn-primary">Ir al centro</a>
    </div>
  </div>
</div>

<script>
  let mediaRecorder;
  let isRecording = false;
  let recordingDescription = '';
  let recordingStream;
let sessionId = ''; // variable global

function toggleRecording() {
  if (!isRecording) {
    document.getElementById('recordingDescription').value = '';
    document.getElementById('recordingModal').style.display = 'flex';
  } else {
    stopRecording();
  }
}

function cancelRecordingModal() {
  document.getElementById('recordingModal').style.display = 'none';
}

function startRecordingWithDescription() {
  const descInput = document.getElementById('recordingDescription').value.trim();
  if (descInput === '') {
    alert('Debe ingresar una descripción');
    return;
  }

  recordingDescription = descInput;
  document.getElementById('recordingModal').style.display = 'none';

  const usersId = document.getElementById('users_id').value; // asegurar valor correcto
  const now = new Date();
  const formattedDate = now.getFullYear().toString() +
  String(now.getMonth() + 1).padStart(2, '0') +
  String(now.getDate()).padStart(2, '0') +
  String(now.getHours()).padStart(2, '0') +
  String(now.getMinutes()).padStart(2, '0') +
  String(now.getSeconds()).padStart(2, '0');
  sessionId = `${usersId}${formattedDate}`; // SessionId correcto

  navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
    recordingStream = stream;
    mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm' });

    mediaRecorder.ondataavailable = event => {
      if (event.data.size > 0) {
        const audioBlob = event.data;
        const formData = new FormData();

        formData.append('File', audioBlob, 'chunk.webm');
        formData.append('Description', recordingDescription);
        formData.append('UsersId', usersId); // UsersId correcto
        formData.append('SessionId', sessionId); // SessionId correcto

        fetch('upload_audio.php', {
          method: 'POST',
          body: formData
        })
        .then(async res => {
          const text = await res.text();
          try {
            const data = JSON.parse(text);
            if (data.status !== 'success') {
              console.error('Error del servidor:', data.message);
            } else {
              // console.log('Subido:', data);
            }
          } catch (e) {
            console.error('Error al parsear JSON:', text);
          }
        })
        .catch(err => {
          console.error('Error al subir audio:', err.message || err);
        });
      }
    };

    mediaRecorder.onerror = (e) => {
      console.error("Error en MediaRecorder:", e.error);
      alert("Ocurrió un error al grabar el audio.");
      stopRecording();
    };

    mediaRecorder.onstop = () => {
      recordingStream.getTracks().forEach(track => track.stop());
      document.getElementById('savedRecordingModal').style.display = 'flex';
    };

    mediaRecorder.start(<?php echo CFG_RECORDS_PARTSMS; ?>); // 60 seconds (60000) & 5 minutes (300000 ms)

    document.getElementById('recordButton').classList.add('recording');
    document.getElementById('recordIcon').classList.replace('fa-headphones', 'fa-stop');
    isRecording = true;
  }).catch(err => {
    alert('No se pudo acceder al micrófono: ' + err.message);
    console.error(err);
  });
}

function stopRecording() {
  if (mediaRecorder && mediaRecorder.state !== "inactive") {
    mediaRecorder.stop();
  }
  document.getElementById('recordButton').classList.remove('recording');
  document.getElementById('recordIcon').classList.replace('fa-stop', 'fa-headphones');
  isRecording = false;
}

function hideSavedRecordingModal() {
  document.getElementById('savedRecordingModal').style.display = 'none';
}
</script>

<!-- boton para pegar -->

<script>
  async function checkClipboardForText() {
    try {
      const permission = await navigator.permissions.query({ name: "clipboard-read" });
      if (permission.state === "granted" || permission.state === "prompt") {
        const clipboardText = await navigator.clipboard.readText();
        const pasteButton = document.getElementById("pasteButton");
        if (clipboardText.trim() !== "") {
          pasteButton.classList.remove("d-none");
        } else {
          pasteButton.classList.add("d-none");
        }
      }
    } catch (err) {
      console.warn("No se pudo acceder al portapapeles:", err);
      document.getElementById("pasteButton").classList.add("d-none");
    }
  }

  function pasteFromClipboard() {
    navigator.clipboard.readText().then(text => {
      const textarea = document.getElementById("userInput");
      textarea.value += text;
      textarea.focus();
    }).catch(err => {
      alert("No se pudo pegar desde el portapapeles.");
      console.error(err);
    });
  }

  // Verificar cada 2 segundos si hay contenido en el portapapeles
  setInterval(checkClipboardForText, 2000);
</script>


<script type="module" src="<?php echo CFG_APP_URL; ?>/assets/js/chat.js?v=1.0.59"></script>


<?php require_once('footer.php'); ?>