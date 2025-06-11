<?php
require_once('../../config.php');
require_once('../../conn_dw.php');

// Función para generar nombres de empresas ficticias
function generarNombre() {
    $prefijos = ['Grupo', 'Corporación', 'Industrias', 'Servicios', 'Inversiones', 'Soluciones', 'Distribuidora', 'Tecnologías'];
    $nucleos = ['Delta', 'Nova', 'Andes', 'Pura Vida', 'TicoTec', 'BioPlus', 'EcoSmart', 'Visionaria', 'Centroamérica', 'Costa Rica'];
    $sufijos = ['S.A.', 'Limitada', 'Corp.', 'Global', 'Holding', 'CR', 'Intl.'];

    return $prefijos[array_rand($prefijos)] . ' ' .
           $nucleos[array_rand($nucleos)] . ' ' .
           $sufijos[array_rand($sufijos)];
}

// Función para generar una fecha aleatoria entre $diasPasados y hoy
function randomFecha($diasPasados = 365, $diasFuturos = 0) {
    $timestamp = strtotime("-" . rand(0, $diasPasados) . " days");
    return date('Y-m-d', $timestamp);
}

// Eliminar y recrear la tabla
$conn_dw->query("DROP TABLE IF EXISTS ventas");

$createTableSQL = "
CREATE TABLE ventas (
    id_venta INT PRIMARY KEY,
    fecha_venta DATE,
    anio_venta VARCHAR(10),
    mes_venta VARCHAR(10),
    cliente_nombre VARCHAR(100),
    producto_nombre VARCHAR(100),
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    monto_colones DECIMAL(12,2),
    monto_dolares DECIMAL(12,2),
    canal_venta VARCHAR(50),
    estado_venta VARCHAR(50),
    region VARCHAR(50),
    vendedor_nombre VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conn_dw->query($createTableSQL) === TRUE) {
    echo "Tabla ventas creada correctamente.\n";
} else {
    die("Error al crear la tabla: " . $conn_dw->error);
}

// Datos de prueba
$canales = ['Web', 'Tienda', 'Partner'];
$estados_venta = ['Completado', 'Anulado'];
$regiones = ['San José', 'Alajuela', 'Heredia', 'Cartago'];
$productos = ['Laptop', 'Mouse', 'Teclado', 'Monitor', 'Impresora', 'Auriculares'];
$vendedores = ['Josué Méndez', 'Emanuel Áviles', 'Manuel Andrés', 'Herbert Poveda'];

$records = 1000;
$insertados = 0;
$tipo_cambio = 505; // ₡ por dólar

for ($i = 1; $i <= $records; $i++) {
    $fecha = randomFecha(1095, 0); // 3 años hacia atrás
    $anio = date('Y', strtotime($fecha));
    $mes = date('n', strtotime($fecha)); // mes sin ceros iniciales

    $cliente = generarNombre();
    $producto = $productos[array_rand($productos)];
    $cantidad = rand(1, 5);
    $precio = rand(1000, 50000) / 100;
    $monto_colones = $cantidad * $precio;
    $monto_dolares = $monto_colones / $tipo_cambio;
    $vendedor = $vendedores[array_rand($vendedores)];

    $sql = "INSERT INTO ventas (
        id_venta, fecha_venta, anio_venta, mes_venta,
        cliente_nombre, producto_nombre,
        cantidad, precio_unitario, monto_colones, monto_dolares,
        canal_venta, estado_venta, region, vendedor_nombre
    ) VALUES (
        $i, '$fecha', $anio, $mes,
        '{$conn_dw->real_escape_string($cliente)}', '$producto',
        $cantidad, $precio, $monto_colones, $monto_dolares,
        '{$canales[array_rand($canales)]}', '{$estados_venta[array_rand($estados_venta)]}',
        '{$regiones[array_rand($regiones)]}', '{$conn_dw->real_escape_string($vendedor)}'
    )";

    if ($conn_dw->query($sql) === TRUE) {
        $insertados++;
    } else {
        echo "Error al insertar el registro $i: " . $conn_dw->error . "\n";
    }
}

echo "Datos insertados correctamente: $insertados registros.\n";
$conn_dw->close();
?>
