<?php

require_once 'src/DashWonline.php';
$config = require 'config.php';
$api = new DashWonline($config['user'], $config['api_token']);

// Datos de la nueva factura
$datosFactura = [
    "clientid" => 1,
    "number" => 1001,
    "date" => "2024-04-01",
    "currency" => 1,
    "subtotal" => 1000.00,
    "total" => 1150.00,
    "billing_street" => "Calle Ficticia 123",
    "allowed_payment_modes" => [1, 2],
];

$datosFactura = $api->addItemAFactura($datosFactura, [
    'description' => 'item 1 description',
    'long_description' => 'item 1 long description',
    'qty' => 2,
    'rate' => 500,
    'order' => 1,
    'unit' => '',
    'taxname' => 'iva|21.00' // Asumiendo que 'taxname' es un array
]);


// Agregar la nueva factura
$response = $api->agregarNuevaFactura($datosFactura);

// Imprimir la respuesta de la API
echo $response;