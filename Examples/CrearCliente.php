<?php

require_once 'src/DashWonline.php';
$config = require 'config.php';
$api = new DashWonline($config['user'], $config['api_token']);

// Datos del cliente de ejemplo
$clienteData = [
    "company" => "Empresa Ejemplo S.A.",
    "vat" => "B12345678",
    "phonenumber" => "+34902020202",
    "website" => "https://empresa-ejemplo.com",
    "default_language" => "es",
    "default_currency" => "EUR",
];

// Llama al método para crear el cliente
try {

    // Imprime la respuesta de la API
    echo $api->crearCliente($clienteData);

} catch (Exception $e) {

    // Manejar la excepción, por ejemplo, registrando el mensaje de error
    error_log($e->getMessage());
    // Opcionalmente, puedes enviar una respuesta o mensaje de error al usuario
    echo "Ocurrió un error al crear el cliente.";
}



