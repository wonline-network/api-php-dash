<?php

require_once 'DashWonline.php';

$api = new ApiWonline(
    "tuCuentaCliente",
    "tuTokenDeAutenticacion"
);

// ID del cliente cuya información deseas solicitar
$idCliente = 123;

// Solicita la información del cliente
$informacionCliente = $api->InfoCliente($idCliente);

// Imprime la información del cliente
echo $informacionCliente;
?>