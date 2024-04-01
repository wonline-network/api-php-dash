<?php

require_once 'src/DashWonline.php';

$api = new DashWonline(
    "",
    "..--"
);

// La palabra clave para buscar
$keysearch = "empresa ejemplo s.a.";

// Solicita la información del cliente
$informacionCliente = $api->buscarCliente($nombreSinTerminacion);

// Solicita información de cliente empleando expresión regular
$informacionCliente = $api->buscarCliente_NE($nombreSinTerminacion);


// Imprime la información del cliente
echo $informacionCliente;