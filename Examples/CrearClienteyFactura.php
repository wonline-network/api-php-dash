<?php
require_once 'src/DashWonline.php';
$config = require 'config.php';
$api = new DashWonline($config['user'], $config['api_token']);

// Datos del cliente
$datosCliente = [
    "company" => "Empresa Ejemplo S.A.",
    "vat" => "ESB123456789",
    "phonenumber" => "+34902123456",
    "website" => "https://empresa-ejemplo.com",
    "default_language" => "es",
    "default_currency" => "EUR",
    "address" => "Calle Ejemplo 123",
    "city" => "Madrid",
    "state" => "Madrid",
    "zip" => "28080",
    "country" => "ES",
    "billing_street" => "Calle Facturación 456",
    "billing_city" => "Madrid",
    "billing_state" => "Madrid",
    "billing_zip" => "28081",
    "billing_country" => "ES",
    "shipping_street" => "Calle Envío 789",
    "shipping_city" => "Barcelona",
    "shipping_state" => "Barcelona",
    "shipping_zip" => "08080",
    "shipping_country" => "ES",
];
// Datos de la factura, incluyendo los items
$datosFactura = [
    "number" => 1005,
    "date" => "2024-04-01",
    "currency" => 1,
    // Asegúrate de que el formato decimal sea correcto 00.00
    "subtotal" => '1000.00',
    // Asegúrate de que el formato decimal sea correcto 00.00
    "total" => '2420.00',
    "billing_street" => "Calle Ficticia 123",
    "allowed_payment_modes[0]" => 1
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

// Crear el cliente y la factura
try {

    // Imprimir la respuesta de la API para la creación de la factura
    echo $api->crearClienteYFactura($datosCliente, $datosFactura);

} catch (Exception $e) {

    // Manejar la excepción, por ejemplo, registrando el mensaje de error
    error_log($e->getMessage());

    // Opcionalmente, puedes enviar una respuesta o mensaje de error al usuario
    echo "Ocurrió un error al crear el cliente y la factura.";
}