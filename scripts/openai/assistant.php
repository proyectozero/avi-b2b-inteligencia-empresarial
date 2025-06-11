<?php
// assistant.php
require_once 'conn.php';
require_once 'functions.php';

$pregunta = "¿Cuáles son los 5 productos más vendidos en abril?";

$sql = generarSQLConOpenAI($pregunta);
echo "Consulta generada:\n$sql\n\n";

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        print_r($fila);
    }
} else {
    echo "No se obtuvieron resultados o hubo un error en la consulta.\n";
}

$conn->close();
?>
