<?php
require_once 'src/DashWonline.php';
$config = require 'config.php';
$api = new DashWonline($config['user'], $config['api_token']);
// ID del cliente a eliminar
$idCliente = 123;

// Llama al método para eliminar el cliente
$response = $api->eliminarCliente($idCliente);

// Imprime la respuesta de la API
echo $response;
?>