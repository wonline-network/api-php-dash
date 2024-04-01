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
     * Crea un nuevo cliente en el sistema.
     *
     * @param array $clienteData Datos del cliente a crear.
     * @return string Respuesta de la API.
     * @throws Exception
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
        // Primero, intentamos crear el cliente
        $respuestaCliente = $this->crearCliente($datosCliente);

        // Aquí asumimos que el nombre de la compañía es único y usamos eso para buscar.
        $nombreCliente = $datosCliente['company'];

        // Buscamos al cliente para obtener su ID
        $respuestaBusqueda = $this->buscarCliente($nombreCliente);
        $resultadoBusqueda = json_decode($respuestaBusqueda, true);

        // Asumiendo que la respuesta de búsqueda es una lista de resultados y el cliente deseado es el primer elemento
        if (!empty($resultadoBusqueda) && isset($resultadoBusqueda[0]['clientid'])) {
            $idCliente = $resultadoBusqueda[0]['clientid'];

            // Preparar los datos de la factura, asegurando que el 'clientid' sea correcto
            $datosFactura['clientid'] = $idCliente;

            // Ahora, crear la factura para el cliente recién creado
            return $this->agregarNuevaFactura($datosFactura);

        } else {
            // Manejar el caso en que no se encuentra el cliente
            return "Error: Cliente no encontrado después de la creación.";
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
