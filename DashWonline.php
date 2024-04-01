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

    /**
     * Crea un nuevo cliente en el sistema.
     *
     * @param array $clienteData Datos del cliente a crear.
     * @return string Respuesta de la API.
     */
    public function crearCliente(array $clienteData): string {
        $path = "customers"; // Ajusta este endpoint a donde se deben enviar los datos para crear un cliente.
        $data = json_encode($clienteData); // Codifica los datos del cliente como JSON.
        $headers = [
            'Content-Type: application/json', // Asume que la API espera JSON.
            'Authorization: Bearer ' . $this->authtoken // Asume que usas el token de autenticación ya configurado en la clase.
        ];
        return $this->post($path, $data, $headers);
    }
    /**
     * Elimina un cliente del sistema.
     *
     * @param int $id ID único del cliente a eliminar.
     * @return string Respuesta de la API.
     */
    public function eliminarCliente(int $id): string {
        $path = "delete/customers/{$id}"; // Construye el path con el ID del cliente.
        $headers = [
            'Authorization: Bearer ' . $this->authtoken // Usa el token de autenticación configurado.
        ];
        return $this->delete($path, $headers);
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

    public function delete($path, array $headers = []): string {
        return $this->request('DELETE', $path, null, $headers);
    }

    /**
     * Realiza una solicitud HTTP a la API.
     *
     * @param string $method Método HTTP ('GET', 'POST', 'PUT', 'DELETE').
     * @param string $path Ruta del endpoint de la API.
     * @param mixed $data Datos a enviar con la solicitud.
     * @param array $headers Headers adicionales para la solicitud.
     * @return string Respuesta de la API.
     * @throws Exception
     */
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
