<?php
require_once 'src/DashWonline.php';
$config = require 'config.php';
$api = new DashWonline($config['user'], $config['api_token']);

// Solicitar la información de impuestos
$listarPagos = $api->listarPagos();

// Imprimir la respuesta de la API
echo $listarPagos;