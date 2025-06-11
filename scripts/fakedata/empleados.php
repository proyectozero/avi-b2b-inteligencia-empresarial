<?php
require_once('../../config.php');
require_once('../../conn_dw.php');
require_once('functions.php');

// Eliminar y recrear la tabla
$conn_dw->query("DROP TABLE IF EXISTS empleados");

$createTableSQL = "
CREATE TABLE empleados (
    id_empleado INT PRIMARY KEY,
    nombre VARCHAR(100),
    departamento VARCHAR(50),
    puesto VARCHAR(100),
    fecha_ingreso DATE,
    salario DECIMAL(10,2),
    estado VARCHAR(50),
    evaluacion VARCHAR(50),
    correo VARCHAR(100),
    telefono VARCHAR(20),
    edad INT,
    genero ENUM('Masculino', 'Femenino', 'Otro')
);
";

if ($conn_dw->query($createTableSQL) === TRUE) {
    echo "Tabla 'empleados' creada correctamente.\n";
} else {
    die("Error al crear la tabla: " . $conn_dw->error);
}

// Datos de prueba
$departamentos = ['Ventas', 'Tecnología', 'Recursos Humanos', 'Finanzas', 'Marketing', 'Logística'];
$puestos = ['Ejecutivo de Ventas', 'Desarrollador', 'Analista de RRHH', 'Contador', 'Diseñador', 'Gerente de Proyecto'];
$estados = ['Activo', 'Suspendido', 'Desvinculado'];
$evaluaciones = ['Excelente', 'Buena', 'Regular'];
$nombres = ['Luis', 'María', 'Carlos', 'Ana', 'Jorge', 'Patricia', 'Fernando', 'Carmen', 'José', 'Lucía'];
$apellidos = ['Pérez', 'Rodríguez', 'González', 'Fernández', 'López', 'Martínez', 'Sánchez', 'Ramírez', 'Torres', 'Díaz'];
$generos = ['Masculino', 'Femenino', 'Otro'];

$records = 50;
$insertados = 0;

for ($i = 1; $i <= $records; $i++) {
    $nombre_completo = $nombres[array_rand($nombres)] . ' ' . $apellidos[array_rand($apellidos)];
    $departamento = $departamentos[array_rand($departamentos)];
    $puesto = $puestos[array_rand($puestos)];
    $fecha_ingreso = randomFecha(3650, 0); // hasta 10 años atrás
    $salario = rand(30000, 120000);
    $estado = $estados[array_rand($estados)];
    $evaluacion = $evaluaciones[array_rand($evaluaciones)];
    $correo = strtolower(str_replace(' ', '.', $nombre_completo)) . '@empresa.com';
    $telefono = '5' . rand(10000000, 99999999);
    $edad = rand(22, 60);
    $genero = $generos[array_rand($generos)];

    $sql = "INSERT INTO empleados (
        id_empleado, nombre, departamento, puesto, fecha_ingreso,
        salario, estado, evaluacion, correo, telefono, edad, genero
    ) VALUES (
        $i, '{$conn_dw->real_escape_string($nombre_completo)}', '{$conn_dw->real_escape_string($departamento)}',
        '{$conn_dw->real_escape_string($puesto)}', '$fecha_ingreso', $salario,
        '{$conn_dw->real_escape_string($estado)}', '{$conn_dw->real_escape_string($evaluacion)}',
        '{$conn_dw->real_escape_string($correo)}', '$telefono', $edad, '$genero'
    )";

    if ($conn_dw->query($sql) === TRUE) {
        $insertados++;
    } else {
        echo "Error al insertar el empleado $i: " . $conn_dw->error . "\n";
    }
}

echo "Datos insertados correctamente: $insertados empleados.\n";
$conn_dw->close();
?>
