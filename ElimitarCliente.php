<?php

require_once 'DashWonline.php';

$api = new ApiWonline("tuCuentaCliente", "tuTokenDeAutenticacion");

// ID del cliente a eliminar
$idCliente = 123;

// Llama al método para eliminar el cliente
$response = $api->eliminarCliente($idCliente);

// Imprime la respuesta de la API
echo $response;
?>