<?php
require_once('../../config.php');
require_once('../../conn_dw.php');
require_once('functions.php'); // Asegúrate de tener funciones como `randomFecha()`, `generarNombre()`

// Eliminar tabla previa si existe
$conn_dw->query("DROP TABLE IF EXISTS inventarios");

$createTableSQL = "
CREATE TABLE inventarios (
    id_compra INT PRIMARY KEY,
    fecha_compra DATE,
    producto_nombre VARCHAR(100),
    cantidad INT,
    costo_unitario DECIMAL(10,2),
    total_compra DECIMAL(12,2),
    fecha_venta DATE NULL,
    precio_venta_unitario DECIMAL(10,2) NULL,
    total_venta DECIMAL(12,2) NULL,
    utilidad DECIMAL(12,2) NULL,
    rentabilidad DECIMAL(5,2) NULL, -- (% utilidad / costo total)
    rotacion ENUM('alta','media','baja') DEFAULT 'media',
    observaciones TEXT,
    usuario_rol ENUM('A','B') DEFAULT 'B'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conn_dw->query($createTableSQL) === TRUE) {
    echo "Tabla inventarios creada correctamente.\n";
} else {
    die("Error al crear la tabla: " . $conn_dw->error);
}

// Datos de prueba
$productos = ['Laptop', 'Mouse', 'Teclado', 'Monitor', 'Impresora', 'Auriculares'];
$records = 1000;
$insertados = 0;

for ($i = 1; $i <= $records; $i++) {
    $fecha_compra = randomFecha(730, 60); // Últimos 2 años hasta hace 2 meses
    $producto = $productos[array_rand($productos)];
    $cantidad = rand(1, 10);
    $costo_unitario = rand(1000, 50000) / 100;
    $total_compra = $cantidad * $costo_unitario;

    // Simular ventas solo para algunos registros
    $vendido = rand(0, 1);
    $fecha_venta = $vendido ? randomFecha(60, 0) : null;
    $precio_venta_unitario = $vendido ? $costo_unitario * (1 + rand(5, 30)/100) : null;
    $total_venta = $vendido ? $precio_venta_unitario * $cantidad : null;
    $utilidad = $vendido ? $total_venta - $total_compra : null;
    $rentabilidad = $vendido ? ($utilidad / $total_compra) * 100 : null;

    // Simular rotación
    $rotacion = ['alta','media','baja'][array_rand([0,1,2])];
    $rol = rand(0, 1) ? 'A' : 'B';

    $sql = "
    INSERT INTO inventarios (
        id_compra, fecha_compra, producto_nombre, cantidad, costo_unitario, total_compra,
        fecha_venta, precio_venta_unitario, total_venta, utilidad, rentabilidad,
        rotacion, observaciones, usuario_rol
    ) VALUES (
        $i, '$fecha_compra', '$producto', $cantidad, $costo_unitario, $total_compra,
        " . ($vendido ? "'$fecha_venta'" : "NULL") . ",
        " . ($vendido ? $precio_venta_unitario : "NULL") . ",
        " . ($vendido ? $total_venta : "NULL") . ",
        " . ($vendido ? $utilidad : "NULL") . ",
        " . ($vendido ? $rentabilidad : "NULL") . ",
        '$rotacion', NULL, '$rol'
    )";

    if ($conn_dw->query($sql) === TRUE) {
        $insertados++;
    } else {
        echo "Error al insertar registro $i: " . $conn_dw->error . "\n";
    }
}

echo "Datos insertados correctamente: $insertados registros.\n";
$conn_dw->close();
?>
