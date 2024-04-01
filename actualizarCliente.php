<?php

require_once 'DashWonline.php';

$api = new ApiWonline("tuCuentaCliente", "tuTokenDeAutenticacion");

// ID del cliente a actualizar y nuevos datos
$idCliente = 123;
$datosCliente = [
    "company" => "Nueva Empresa S.A.",
    // Añadir o actualizar los campos necesarios...
];

// Actualizar la información del cliente
$response = $api->actualizarCliente($idCliente, $datosCliente);

// Imprimir la respuesta de la API
echo $response;