<?php

$api = new ApiWonline("tuCuentaCliente", "tuTokenDeAutenticacion");

// Datos del cliente de ejemplo
$clienteData = [
    "company" => "Empresa Ejemplo S.A.",
    "vat" => "B12345678",
    "phonenumber" => "+34902020202",
    "website" => "https://empresa-ejemplo.com",
    "groups_in" => [1, 2],
    "default_language" => "es",
    "default_currency" => "EUR",
    // Añade el resto de campos necesarios...
];

// Llama al método para crear el cliente
$response = $api->crearCliente($clienteData);

// Imprime la respuesta de la API
echo $response;


?>