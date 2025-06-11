<?php
$conn_dw = new mysqli($cfg_dw_dbhost, $cfg_dw_dbuser, $cfg_dw_dbpass, $cfg_dw_dbname);

if ($conn_dw->connect_error) {
    die("Conexión fallida: " . $conn_dw->connect_error);
}