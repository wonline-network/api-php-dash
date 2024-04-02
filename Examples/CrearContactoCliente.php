<?php


require_once 'src/DashWonline.php';
$config = require 'config.php';
$api = new DashWonline($config['user'], $config['api_token']);

$datosContacto = [
    "customer_id" => 68,
    "firstname" => "Juan",
    "lastname" => "Pérez",
    "email" => "github@angelluis.es",
    'send_set_password_email' => true
    // Añade aquí otros campos opcionales según sea necesario
];

$response = $api->crearContactoCliente($datosContacto);
// Imprime la información del cliente
echo $response;