<?php
if (version_compare(phpversion(), '7.0.0', '<')) {
    die('Este script requiere al menos PHP 7.0.0');
}
/**
 * Clase ApiWonline
 *
 * Esta clase proporciona una envoltura para realizar llamadas API utilizando cURL.
 * Ha sido creada para la integración con el sistema de Wonline Network LLC.
 *
 * @package Wonline Network LLC API Wrapper
 * @version 1.1
 * @author Ángel Luis Marino
 * @license Propietario - Uso exclusivo de Wonline Network LLC y partes autorizadas.
 *
 * Derechos de autor (C) [2022] Wonline Network LLC. Todos los derechos reservados.
 *
 * NOTA: Este código es parte de las operaciones internas de Wonline Network LLC y su uso
 * está restringido a aplicaciones autorizadas por Wonline Network LLC. Para más información,
 * contactar a info@wonline.network.
 */

class ApiWonline {
    private $base_url;
    private $authtoken;
    private $curl;


    /**
     * Constructor de la clase ApiWonline.
     *
     * @param string $clientAccount Nombre de la cuenta de cliente para construir la URL base.
     * @param string $authtoken Token de autenticación para las solicitudes.
     */
    public function __construct($clientAccount, $authtoken) {
        $this->base_url = "https://dash.wonlinenetwork.llc/{$clientAccount}/ps/api/";
        $this->authtoken = $authtoken;
        $this->curl = curl_init();
    }

    public function get($path, array $headers = []): string {
        return $this->request($path, 'GET', null, $headers);
    }

    public function post($path, $data, array $headers = []): string {
        return $this->request($path, 'POST', $data, $headers);
    }

    public function put($path, $data, array $headers = []): string {
        return $this->request($path, 'PUT', $data, $headers);
    }

    private function request($path, $method, $data = null, array $headers = []): string {
        $url = $this->base_url . $path;
        $headers = $this->addAuthorizationHeader($headers);

        if (is_array($data)) {
            $data = json_encode($data);
            $headers[] = 'Content-Type: application/json';
        }

        curl_setopt_array($this->curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($this->curl);
        if ($response === false) {
            throw new Exception(curl_error($this->curl), curl_errno($this->curl));
        }

        return $response;
    }
    private function addAuthorizationHeader(array $headers): array {
        if (!empty($this->authtoken)) {
            $headers[] = 'authtoken: ' . $this->authtoken;
        }
        return $headers;
    }
    public function __destruct() {
        curl_close($this->curl);
    }
}
