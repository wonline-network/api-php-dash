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
    // Añade el resto de campos necesarios...
];

// Llama al método para crear el cliente
try {
    $response = $api->crearCliente($clienteData);
} catch (Exception $e) {
}

// Imprime la respuesta de la API
echo $response;

