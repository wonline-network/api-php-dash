<?php
require_once 'src/DashWonline.php';
$config = require 'config.php';
$api = new DashWonline($config['user'], $config['api_token']);

$nombre_unico_cliente = rand(0,321654987);
// Datos del cliente
$datosCliente = [
    "company" => $nombre_unico_cliente." Empresa Ejemplo S.A.",
    "vat" => "ESB".$nombre_unico_cliente,
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
    "number" => rand(1007,1007100710071007),
    "date" => "2024-04-01",
    "currency" => 1,
    // Asegúrate de que el formato decimal sea correcto 00.00

    "billing_street" => "Calle Ficticia 123",
    "allowed_payment_modes[0]" => 'stripe',

  /*
    "recurring" => 1, // Optional. recurring 1 to 12 or custom
    "repeat_type_custom" => "month", // Optional. if recurring is custom set gap option day/week/month/year
    "sale_agent" => 1
  */
];

$datosFactura = $api->addItemAFactura(
    $datosFactura, [
        'newitems' => [[
                'description' => 'item 1 description',
                'long_description' => 'item 1 long description',
                'qty' => 1,
                'rate' => 1,
                'order' => 1,
                'unit' => '',
            'taxname' => 'iva|21.00' // Este es un ejemplo usa el nombre de tu taxname1
            ],[
                'description' => 'item 2 description',
                'long_description' => 'item 2 long description',
                'qty' => 1,
                'rate' => 1,
                'order' => 1,
                'unit' => '',
            'taxname' => 'iva|21.00' // Este es un ejemplo usa el nombre de tu taxname2
        ],[
            'description' => 'item 3 description',
            'long_description' => 'item 3 long description',
            'qty' => 1,
            'rate' => 1,
            'order' => 1,
            'unit' => '',
            'taxname' => 'iva|21.00' // Este es un ejemplo usa el nombre de tu taxname2
        ]]
    ]);


$datosContacto = [
    "firstname" => "Juan",
    "lastname" => "Pérez",
    "email" => "github".rand(0,321654987)."@angelluis.es",
    'send_set_password_email' => true
    // Añade aquí otros campos opcionales según sea necesario
];

// Crear el cliente y la factura
try {

    // Imprimir la respuesta de la API para la creación de la factura
    print_r($api->crearClienteYFactura($datosCliente, $datosFactura, $datosContacto));

} catch (Exception $e) {

    // Manejar la excepción, por ejemplo, registrando el mensaje de error
    error_log($e->getMessage());

    // Opcionalmente, puedes enviar una respuesta o mensaje de error al usuario
    echo "Ocurrió un error al crear el cliente y la factura.";
}