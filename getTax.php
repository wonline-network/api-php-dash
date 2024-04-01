<?php

require_once 'src/DashWonline.php';

$api = new DashWonline(
    "",
    "..--"
);


// Solicitar la información de impuestos
$informacionImpuestos = $api->solicitarDatosImpuestos();

// Imprimir la respuesta de la API
echo $informacionImpuestos;