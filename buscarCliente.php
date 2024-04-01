<?php

require_once 'DashWonline.php';

$api = new ApiWonline(
    "tuCuentaCliente",
    "tuTokenDeAutenticacion"
);

// La palabra clave para buscar
$keysearch = "palabraclave";

// Solicita la información del cliente
try {
    $informacionCliente = $api->buscarCliente($idCliente);
} catch (Exception $e) {

}

// Imprime la información del cliente
echo $informacionCliente;
?>