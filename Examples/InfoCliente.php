<?php
require_once 'src/DashWonline.php';
$config = require 'config.php';
$api = new DashWonline($config['user'], $config['api_token']);

// ID del cliente cuya información deseas solicitar
$idCliente = 123;

// Solicita la información del cliente
$informacionCliente = $api->InfoCliente($idCliente);

// Imprime la información del cliente
echo $informacionCliente;
?>