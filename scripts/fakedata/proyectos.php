<?php
require_once('../../config.php');
require_once('../../conn_dw.php');
require_once('functions.php');

// Eliminar y recrear la tabla
$conn_dw->query("DROP TABLE IF EXISTS proyectos");

$createTableSQL = "
CREATE TABLE proyectos (
    id_proyecto INT PRIMARY KEY,
    nombre_proyecto VARCHAR(100),
    descripcion TEXT,
    fecha_inicio DATE,
    fecha_fin DATE,
    estado VARCHAR(50),
    prioridad VARCHAR(20),
    lider VARCHAR(100),
    tipo VARCHAR(20),
    dias_atraso INT,
    porcentaje_progreso INT
);
";

if ($conn_dw->query($createTableSQL) === TRUE) {
    echo "Tabla 'proyectos' creada correctamente.\n";
} else {
    die("Error al crear la tabla: " . $conn_dw->error);
}

// Datos de prueba
$estados_proyecto = ['En progreso', 'Finalizado', 'Pausado'];
$prioridades = ['Alta', 'Media', 'Baja'];
$lideres = ['Josué Méndez', 'Herbert Poveda', 'Mano', 'Emmanuel Áviles', 'Pablo Méndez', 'Keylor Meédez', 'Derek'];
$nombres_proyectos = ['Optimización Web', 'Lanzamiento App', 'Migración de Servidor', 'Campaña Marketing', 'Desarrollo Interno', 'Expansión CRM', 'Rediseño UI'];
$descripciones = [
    'Proyecto enfocado en mejorar el rendimiento del sitio.',
    'Nueva aplicación para clientes móviles.',
    'Reubicación de todos los servicios a un nuevo servidor.',
    'Estrategia de marketing digital para el nuevo producto.',
    'Mejoras internas en sistemas existentes.',
    'Integración con nuevo sistema de CRM.',
    'Actualizar el diseño visual de la interfaz.'
];
$tipos = ['Tarea', 'Proyecto'];

$records = 300;
$insertados = 0;

$hoy = date('Y-m-d');

for ($i = 1; $i <= $records; $i++) {
    $nombre = $nombres_proyectos[array_rand($nombres_proyectos)] . " #" . rand(1, 99);
    $descripcion = $descripciones[array_rand($descripciones)];

    $fecha_inicio = randomFecha(730, 0); // hasta 2 años atrás
    $dias_duracion = rand(15, 180);
    $fecha_fin = date('Y-m-d', strtotime($fecha_inicio . " +$dias_duracion days"));

    $estado = $estados_proyecto[array_rand($estados_proyecto)];
    $prioridad = $prioridades[array_rand($prioridades)];
    $lider = $lideres[array_rand($lideres)];
    $tipo = $tipos[array_rand($tipos)];

    // Calcular progreso y atraso
    $dias_totales = (strtotime($fecha_fin) - strtotime($fecha_inicio)) / 86400;
    $dias_transcurridos = (strtotime($hoy) - strtotime($fecha_inicio)) / 86400;
    $dias_atraso = 0;
    $porcentaje_progreso = 0;

    if ($estado === 'Finalizado') {
        $porcentaje_progreso = 100;
    } elseif ($estado === 'En progreso') {
        $porcentaje_progreso = min(100, max(1, round(($dias_transcurridos / $dias_totales) * 100)));
    } elseif ($estado === 'Pausado') {
        $porcentaje_progreso = rand(10, 50);
    }

    if ($estado !== 'Finalizado' && strtotime($hoy) > strtotime($fecha_fin)) {
        $dias_atraso = (strtotime($hoy) - strtotime($fecha_fin)) / 86400;
    }

    $sql = "INSERT INTO proyectos (
        id_proyecto, nombre_proyecto, descripcion,
        fecha_inicio, fecha_fin, estado, prioridad, lider,
        tipo, dias_atraso, porcentaje_progreso
    ) VALUES (
        $i, '{$conn_dw->real_escape_string($nombre)}', '{$conn_dw->real_escape_string($descripcion)}',
        '$fecha_inicio', '$fecha_fin', '{$conn_dw->real_escape_string($estado)}', 
        '{$conn_dw->real_escape_string($prioridad)}', '{$conn_dw->real_escape_string($lider)}',
        '{$conn_dw->real_escape_string($tipo)}', $dias_atraso, $porcentaje_progreso
    )";

    if ($conn_dw->query($sql) === TRUE) {
        $insertados++;
    } else {
        echo "Error al insertar el proyecto $i: " . $conn_dw->error . "\n";
    }
}

echo "Datos insertados correctamente: $insertados proyectos.\n";
$conn_dw->close();
?>
