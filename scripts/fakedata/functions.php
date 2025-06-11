<?php 
// Helpers
function generarNombre() {
    $nombres = ['Luis', 'María', 'Carlos', 'Ana', 'Pedro', 'Sofía', 'Daniel', 'Laura'];
    $apellidos = ['Gómez', 'Rodríguez', 'Martínez', 'López', 'Fernández', 'Jiménez'];
    return $nombres[array_rand($nombres)] . ' ' . $apellidos[array_rand($apellidos)];
}

function generarCorreo($nombre) {
    $dominios = ['@empresa.com', '@negocio.com', '@compania.net'];
    $nombre_simple = strtolower(str_replace(' ', '.', $nombre));
    return $nombre_simple . $dominios[array_rand($dominios)];
}

// Función para generar una fecha aleatoria entre hoy y hace $diasMax días
function randomFecha($diasMax, $diasMin = 0) {
    $timestamp = strtotime("-" . rand($diasMin, $diasMax) . " days");
    return date("Y-m-d", $timestamp);
}

function generarNombreProyecto() {
    $adjetivos = ['Global', 'Innovador', 'Digital', 'Sostenible', 'Ágil', 'Integral', 'Eficiente', 'Inteligente'];
    $sustantivos = ['Transformación', 'Plataforma', 'Desarrollo', 'Optimización', 'Automatización', 'Expansión', 'Solución', 'Implementación'];
    $sectores = ['Financiero', 'Educativo', 'Salud', 'Logística', 'Comercial', 'Tecnológico', 'Ambiental', 'Industrial'];

    return $adjetivos[array_rand($adjetivos)] . ' ' .
           $sustantivos[array_rand($sustantivos)] . ' ' .
           'en ' . $sectores[array_rand($sectores)];
}

function generarPuesto($departamento) {
    $puestos = [
        'Ventas' => ['Ejecutivo de Ventas', 'Gerente de Ventas', 'Asistente Comercial', 'Coordinador de Ventas'],
        'Tecnología' => ['Desarrollador', 'Analista de Sistemas', 'Ingeniero de Datos', 'Administrador de Redes', 'QA Tester'],
        'Recursos Humanos' => ['Especialista en Reclutamiento', 'Analista de Nómina', 'Coordinador de RH', 'Gerente de RH'],
        'Finanzas' => ['Contador', 'Analista Financiero', 'Tesorero', 'Auditor Interno']
    ];

    if (isset($puestos[$departamento])) {
        return $puestos[$departamento][array_rand($puestos[$departamento])];
    }
    // Si el departamento no está en el arreglo, se pone un puesto genérico
    return 'Empleado';
}
