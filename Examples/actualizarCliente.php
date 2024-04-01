<?php
require_once 'src/DashWonline.php';
$config = require 'config.php';
$api = new DashWonline($config['user'], $config['api_token']);

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