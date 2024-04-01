<?php

require_once 'DashWonline.php';

$api = new ApiWonline(
    "tuCuentaCliente",
    "tuTokenDeAutenticacion"
);


// Datos del cliente
$datosCliente = [
    "company" => "Empresa Ejemplo S.A.",
    // otros datos del cliente...
];

// Datos de la factura, incluyendo los items
$datosFactura = [
    "number" => 1001,
    "date" => "2024-04-01",
    "currency" => 1,
    "newitems" => [
        [
            "description" => "Artículo #1",
            "long_description" => "Descripción más larga del artículo #1",
            "quantity" => 2,
            "unit" => "unidad",
            "rate" => 500,
            "taxname" => "IVA 21%"
        ],
        // Más items según sea necesario...
    ],
    "subtotal" => 1000.00,
    "total" => 1210.00,
    "billing_street" => "Calle Ficticia 123",
    "allowed_payment_modes" => [1, 2],
    // otros datos de la factura...
];

// Crear el cliente y la factura
$response = $api->crearClienteYFactura($datosCliente, $datosFactura);

// Imprimir la respuesta de la API para la creación de la factura
echo $response;