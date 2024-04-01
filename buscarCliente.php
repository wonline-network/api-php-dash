<?php

require_once 'src/DashWonline.php';

$api = new DashWonline(
    "",
    "..--"
);

// La palabra clave para buscar
$keysearch = "empresa ejemplo s.a.";

// Solicita la información del cliente

// Expresión regular para buscar terminaciones comunes de tipos de empresa
// Ajusta la lista según las necesidades específicas de tu caso de uso
$expresionRegular = '/\s+(S\.A\.|S\.L\.|LLC|Inc\.|Corp\.)$/i';

// Reemplazar la terminación encontrada por una cadena vacía
$nombreSinTerminacion = preg_replace('/\s+(S\.A\.|S\.L\.|LLC|Inc\.|Corp\.)$/i', '', $nombreEmpresa);
try {
    $informacionCliente = $api->buscarCliente($nombreSinTerminacion);
} catch (Exception $e) {

}

// Imprime la información del cliente
echo $informacionCliente;
?>