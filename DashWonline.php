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
    private string $base_url;
    private string $authtoken;
    private $curl;


    /**
     * Constructor de la clase ApiWonline.
     *
     * @param string $clientAccount Nombre de la cuenta de cliente para construir la URL base.
     * @param string $authtoken Token de autenticación para las solicitudes.
     */
    public function __construct(string $clientAccount, string $authtoken) {
        $this->base_url = "https://dash.wonlinenetwork.llc/{$clientAccount}/ps/api/";
        $this->authtoken = $authtoken;
        $this->curl = curl_init();
    }

    /**
     * Crea un nuevo cliente y devuelve su ID.
     *
     * @param array $datosCliente Datos del cliente a crear.
     * @return string ID del cliente recién creado o mensaje de error.
     * @throws Exception
     */
    public function crearCliente(array $datosCliente): string {
        // Endpoint para la creación del cliente
        $path = "clientes";
        $headers = ['Content-Type: application/json'];
        $data = json_encode($datosCliente);

        // Enviar solicitud para crear el cliente
        $respuestaCliente = $this->post($path, $data, $headers);
        $resultadoCliente = json_decode($respuestaCliente, true);

        // Verificar la respuesta de la creación del cliente
        if (!empty($resultadoCliente) && $resultadoCliente['status']) {
            // Si se indica que el cliente fue añadido exitosamente, buscar al cliente para obtener su ID
            // Asumiendo que 'company' puede usarse para buscar de manera única al cliente
            // Buscar al cliente por el nombre de la empresa
            $clientes = json_decode(
                $this->buscarCliente(
                    $datosCliente['company']
                ), true
            );

            // Implementar la lógica para seleccionar al cliente correcto de la lista, si es necesario
            // Esto es un ejemplo simplificado, ajusta según la estructura de tu respuesta de búsqueda
            foreach ($clientes as $cliente) {
                if ($cliente['name'] === $datosCliente['company']) {
                    return $cliente['clientid']; // Devolver el ID del cliente encontrado
                }
            }

            return "Cliente creado, pero no se encontró en la búsqueda inmediata.";
        } else {
            // Manejar el caso de error en la creación
            return "Error al crear el cliente: " . $resultadoCliente['message'];
        }
    }

    /**
     * Solicita la información de un cliente específico por su ID único.
     *
     * @param int $id ID único del cliente.
     * @return string Respuesta de la API.
     * @throws Exception
     */
    public function InfoCliente(int $id): string {
        $path = "customers/{$id}"; // Construye el path con el ID del cliente.
        $headers = [
            'Authorization: Bearer ' . $this->authtoken // Usa el token de autenticación configurado.
        ];
        return $this->get($path, $headers);
    }

    /**
     * Elimina un cliente del sistema.
     *
     * @param int $id ID único del cliente a eliminar.
     * @return string Respuesta de la API.
     * @throws Exception
     */
    public function eliminarCliente(int $id): string {
        $path = "delete/customers/{$id}"; // Construye el path con el ID del cliente.
        $headers = [
            'Authorization: Bearer ' . $this->authtoken // Usa el token de autenticación configurado.
        ];
        return $this->delete($path, $headers);
    }

    /**
     * Busca clientes utilizando una palabra clave.
     *
     * @param string $keysearch La palabra clave para la búsqueda.
     * @return string Respuesta de la API.
     * @throws Exception
     */
    public function buscarCliente(string $keysearch): string {
        $path = "customers/search/{$keysearch}";
        return $this->get($path);
    }

    /**
     * Agrega una nueva factura.
     *
     * @param array $datosFactura Datos de la factura a agregar.
     * @return string Respuesta de la API.
     */
    public function agregarNuevaFactura(array $datosFactura): string {
        $path = "invoices"; // Endpoint para agregar una nueva factura.
        $headers = ['Content-Type: application/json']; // Asume que la API espera JSON.
        $data = json_encode($datosFactura); // Codifica los datos de la factura a JSON.
        return $this->post($path, $data, $headers);
    }

    /**
     * Actualiza la información de un cliente existente.
     *
     * @param int $id ID único del cliente a actualizar.
     * @param array $datosCliente Datos del cliente a actualizar.
     * @return string Respuesta de la API.
     * @throws Exception
     */
    public function actualizarCliente(int $id, array $datosCliente): string {
        $path = "customers/{$id}";
        $headers = ['Content-Type: application/json']; // Asume que la API espera JSON.
        $data = json_encode($datosCliente);
        return $this->put($path, $data, $headers);
    }

    /**
     * Crea un cliente y luego le crea una factura.
     *
     * @param array $datosCliente Datos del cliente a crear.
     * @param array $datosFactura Datos de la factura a crear.
     * @return string Respuesta de la creación de la factura.
     */
    public function crearClienteYFactura(array $datosCliente, array $datosFactura): string {
        // Intentar crear el cliente y obtener el ID del cliente o un mensaje de error
        $idCliente = $this->crearCliente($datosCliente);

        // Verificar si se obtuvo un ID de cliente
        if (is_numeric($idCliente)) {
            // Si se tiene un ID, preparar y enviar la factura para el cliente recién creado
            $datosFactura['clientid'] = $idCliente;
            $respuestaFactura = $this->agregarNuevaFactura($datosFactura);
            return $respuestaFactura;
        } else {
            // Si no se obtuvo un ID de cliente, devolver el mensaje de error
            return $idCliente; // Aquí idCliente contiene el mensaje de error
        }
    }

    /**
     * @throws Exception
     */
    public function get($path, array $headers = []): string {
        return $this->request($path, 'GET', null, $headers);
    }

    /**
     * @throws Exception
     */
    public function post($path, $data, array $headers = []): string {
        return $this->request($path, 'POST', $data, $headers);
    }

    /**
     * @throws Exception
     */
    public function put($path, $data, array $headers = []): string {
        return $this->request($path, 'PUT', $data, $headers);
    }

    /**
     * @throws Exception
     */
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
    private function request($method, $path, $data = null, array $headers = []): string {
        $url = $this->base_url . $path;
        $headers = $this->addAuthorizationHeader($headers);

        curl_setopt_array($this->curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        if (!empty($data)) {
            if (is_array($data)) {
                $data = json_encode($data);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
                // Asegúrate de que 'Content-Type: application/json' esté en $headers si es necesario
            } else {
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
            }
        }

        $response = curl_exec($this->curl);
        if ($response === false) {
            throw new Exception(curl_error($this->curl), curl_errno($this->curl));
        }

        return $response;
    }
    private function addAuthorizationHeader(array $headers): array {
        $headers[] = 'Authorization: Bearer ' . $this->authtoken;
        return $headers;
    }
    public function __destruct() {
        curl_close($this->curl);
    }
}
