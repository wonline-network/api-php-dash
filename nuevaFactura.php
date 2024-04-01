<?php

require_once 'DashWonline.php';

$api = new ApiWonline("tuCuentaCliente", "tuTokenDeAutenticacion");

// Datos de la nueva factura
$datosFactura = [
    "clientid" => 1,
    "number" => 1001,
    "date" => "2024-04-01",
    "currency" => 1,
    "newitems" => [
        // Detalles de los artículos aquí
    ],
    "subtotal" => 1000.00,
    "total" => 1150.00,
    "billing_street" => "Calle Ficticia 123",
    "allowed_payment_modes" => [1, 2],
    // Añadir más campos según sea necesario...
];

// Agregar la nueva factura
$response = $api->agregarNuevaFactura($datosFactura);

// Imprimir la respuesta de la API
echo $response;