<?php
if (version_compare(phpversion(), '7.0.0', '<')) {
    die('Este script requiere al menos PHP 7.0.0');
}
/**
 * Clase DashWonline
 *
 * Esta clase proporciona una envoltura para realizar llamadas API utilizando cURL.
 * Ha sido creada para la integración con el sistema de Wonline Network LLC.
 *
 * @package Wonline Network LLC API Wrapper
 * @version 1.4
 * @author Ángel Luis Marino
 * @license Propietario - Uso exclusivo de Wonline Network LLC y partes autorizadas.
 *
 * Derechos de autor (C) [2022] Wonline Network LLC. Todos los derechos reservados.
 *
 * NOTA: Este código es parte de las operaciones internas de Wonline Network LLC y su uso
 * está restringido a aplicaciones autorizadas por Wonline Network LLC. Para más información,
 * contactar a info@wonline.network.
 */

class DashWonline {
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

     * @api {post} api/customers Add New Customer
     * @apiName PostCustomer
     * @apiGroup Customers
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {String} company               Mandatory Customer company.
     * @apiParam {String} [vat]                 Optional Vat.
     * @apiParam {String} [phonenumber]         Optional Customer Phone.
     * @apiParam {String} [website]             Optional Customer Website.
     * @apiParam {Number[]} [groups_in]         Optional Customer groups.
     * @apiParam {String} [default_language]    Optional Customer Default Language.
     * @apiParam {String} [default_currency]    Optional default currency.
     * @apiParam {String} [address]             Optional Customer address.
     * @apiParam {String} [city]                Optional Customer City.
     * @apiParam {String} [state]               Optional Customer state.
     * @apiParam {String} [zip]                 Optional Zip Code.
     * @apiParam {String} [partnership_type]    Optional Customer partnership type.
     * @apiParam {String} [country]             Optional country.
     * @apiParam {String} [billing_street]      Optional Billing Address: Street.
     * @apiParam {String} [billing_city]        Optional Billing Address: City.
     * @apiParam {Number} [billing_state]       Optional Billing Address: State.
     * @apiParam {String} [billing_zip]         Optional Billing Address: Zip.
     * @apiParam {String} [billing_country]     Optional Billing Address: Country.
     * @apiParam {String} [shipping_street]     Optional Shipping Address: Street.
     * @apiParam {String} [shipping_city]       Optional Shipping Address: City.
     * @apiParam {String} [shipping_state]      Optional Shipping Address: State.
     * @apiParam {String} [shipping_zip]        Optional Shipping Address: Zip.
     * @apiParam {String} [shipping_country]    Optional Shipping Address: Country.
     *
     * @apiParamExample {Multipart Form} Request-Example:
     *   array (size=22)
     *     'company' => string 'Themesic Interactive' (length=38)
     *     'vat' => string '123456789' (length=9)
     *     'phonenumber' => string '123456789' (length=9)
     *     'website' => string 'AAA.com' (length=7)
     *     'groups_in' =>
     *       array (size=2)
     *         0 => string '1' (length=1)
     *         1 => string '4' (length=1)
     *     'default_currency' => string '3' (length=1)
     *     'default_language' => string 'english' (length=7)
     *     'address' => string '1a The Alexander Suite Silk Point' (length=27)
     *     'city' => string 'London' (length=14)
     *     'state' => string 'London' (length=14)
     *     'zip' => string '700000' (length=6)
     *     'country' => string '243' (length=3)
     *     'billing_street' => string '1a The Alexander Suite Silk Point' (length=27)
     *     'billing_city' => string 'London' (length=14)
     *     'billing_state' => string 'London' (length=14)
     *     'billing_zip' => string '700000' (length=6)
     *     'billing_country' => string '243' (length=3)
     *     'shipping_street' => string '1a The Alexander Suite Silk Point' (length=27)
     *     'shipping_city' => string 'London' (length=14)
     *     'shipping_state' => string 'London' (length=14)
     *     'shipping_zip' => string '700000' (length=6)
     *     'shipping_country' => string '243' (length=3)
     *
     *
     * @apiSuccess {Boolean} status Request status.
     * @apiSuccess {String} message Customer add successful.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "clientid": true,
     *       "message": "Customer add successful."
     *     }
     *
     * @apiError {Boolean} status Request status.
     * @apiError {String} message Customer add fail.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Customer add fail."
     *     }
     *
     */
    public function crearCliente(array $datosCliente): string {
        // Enviar solicitud para crear el cliente retornando el id cliente
        $dc = json_decode($this->post("customers", $datosCliente), true);
        if($dc['status']){
            return $dc['clientid'];
        }
        return $dc['message'];
    }

    /**
     * Solicita la información de un cliente específico por su ID único.
     *
     * @param int $id ID único del cliente.
     * @return string Respuesta de la API.
     * @throws Exception
     */
    public function InfoCliente(int $id): string {
        // Construye el path con el ID del cliente.
        return $this->get("customers/{$id}");
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
        return $this->delete($path);
    }

    /**
     * Busca clientes utilizando una palabra clave.
     *
     * @param string $keysearch La palabra clave para la búsqueda.
     * @return string Respuesta de la API.
     * @throws Exception
     */
    public function buscarCliente(string $keysearch): string {
        return $this->get("customers/search/{$keysearch}");
    }

    /**
     * Busca clientes por nombre de empresa utilizando una palabra clave.
     *
     * @param string $keysearch La palabra clave para la búsqueda.
     * @return string Respuesta de la API.
     * @throws Exception
     */
    public function buscarCliente_NE(string $keysearch): string {
        // Quitamos la terminación de nombres de empresa SA SL LLC INC etc
        $keysearch = preg_replace('/\s+(S\.A\.|S\.L\.|LLC|Inc\.|Corp\.)$/i', '', $keysearch);
        return $this->get("customers/search/{$keysearch}");
    }

    /**
     * Agrega una nueva factura.
     *
     * @param array $datosFactura Datos de la factura a agregar.
     * @return string Respuesta de la API.
     * @throws Exception
     *
     * @api {put} api/invoices/:id Update invoice
     * @apiVersion 0.1.0
     * @apiName PutInvoice
     * @apiGroup Invoices
     *
     * @apiHeader {String} Authorization Basic Access Authentication token.
     *
     * @apiParam {Number}  clientid                     Mandatory Customer id.
     *
     * @apiParam {Number}  clientid                       Mandatory. Customer id
     * @apiParam {Number}  number                         Mandatory. Invoice Number
     * @apiParam {Date}    date                           Mandatory. Invoice Date
     * @apiParam {Number}  currency                       Mandatory. currency field
     * @apiParam {Array}   newitems                       Mandatory. New Items to be added
     * @apiParam {Decimal}   subtotal                   Mandatory. calculation based on item Qty, Rate and Tax
     * @apiParam {Decimal}   total                       Mandatory. calculation based on subtotal, Discount and Adjustment
     * @apiParam {String}  billing_street                 Mandatory. Street Address
     * @apiParam {Array}    allowed_payment_modes          Mandatory. Payment modes
     * @apiParam {String}  [billing_city]                 Optional. City Name for billing
     * @apiParam {String}  [billing_state]                Optional. Name of state for billing
     * @apiParam {Number}  [billing_zip]                  Optional. Zip code
     * @apiParam {Number}  [billing_country]              Optional. Country code
     * @apiParam {boolean} [include_shipping="no"]        Optional. set yes if you want add Shipping Address
     * @apiParam {boolean} [show_shipping_on_invoice]     Optional. Shows shipping details in invoice.
     * @apiParam {String}  [shipping_street]              Optional. Address of shipping
     * @apiParam {String}  [shipping_city]                Optional. City name for shipping
     * @apiParam {String}  [shipping_state]               Optional. Name of state for shipping
     * @apiParam {Number}  [shipping_zip]                 Optional. Zip code for shipping
     * @apiParam {Number}  [shipping_country]             Optional. Country code
     * @apiParam {Date}    [duedate]                      Optional. Due date for Invoice
     * @apiParam {boolean} [cancel_overdue_reminders]       Optional. Prevent sending overdue remainders for invoice
     * @apiParam {String}  [tags]                         Optional. TAGS comma separated
     * @apiParam {Number}  [sale_agent]                   Optional. Sale Agent name
     * @apiParam {String}  [recurring]                    Optional. recurring 1 to 12 or custom
     * @apiParam {String}  [discount_type]                Optional. before_tax / after_tax discount type
     * @apiParam {Number}  [repeat_every_custom]          Optional. if recurring is custom set number gap
     * @apiParam {String}  [repeat_type_custom]           Optional. if recurring is custom set gap option day/week/month/year
     * @apiParam {Number}  [cycles]                       Optional. number of cycles 0 for infinite
     * @apiParam {String}  [adminnote]                    Optional. notes by admin
     * @apiParam {Array}   [items]                        Optional. Existing items with Id
     * @apiParam {Array}   [removed_items]                   Optional. Items to be removed
     * @apiParam {String}    [clientnote]                   Optional. client notes
     * @apiParam {String}    [terms]                       Optional. Terms
     *
     * @apiParamExample {json} Request-Example:
     *  {
     *        "clientid": "1",
     *        "billing_street": "billing address",
     *        "billing_city": "billing city name",
     *        "billing_state": "billing state name",
     *        "billing_zip": "billing zip code",
     *        "billing_country": "",
     *        "include_shipping": "on",
     *        "show_shipping_on_invoice": "on",
     *        "shipping_street": "shipping address",
     *        "shipping_city": "city name",
     *        "shipping_state": "state name",
     *        "shipping_zip": "zip code",
     *        "shipping_country": "",
     *        "number": "000001",
     *        "date": "2020-08-28",
     *        "duedate": "2020-09-27",
     *        "cancel_overdue_reminders": "on",
     *        "tags": "TAG 1,TAG 2",
     *        "allowed_payment_modes": [
     *            "1","2"
     *        ],
     *        "currency": "1",
     *        "sale_agent": "1",
     *        "recurring": "custom",
     *        "discount_type": "before_tax",
     *        "repeat_every_custom": "7",
     *        "repeat_type_custom": "day",
     *        "cycles": "0",
     *        "adminnote": "TEST",
     *        "show_quantity_as": "1",
     *        "items": {
     *            "1": {
     *                "itemid": "1",
     *                "order": "1",
     *                "description": "item description",
     *                "long_description": "item long description",
     *                "qty": "1",
     *                "unit": "1",
     *                "rate": "10.00"
     *            }
     *        },
     *        "removed_items": [
     *            "2",
     *            "3"
     *        ],
     *        "newitems": {
     *            "2": {
     *                "order": "2",
     *                "description": "item 2 description",
     *                "long_description": "item 2 logn description",
     *                "qty": "1",
     *                "unit": "",
     *                "rate": "100.00"
     *            }
     *        },
     *        "subtotal": "10.00",
     *        "discount_percent": "10",
     *        "discount_total": "1.00",
     *        "adjustment": "1",
     *        "total": "10.00",
     *        "clientnote": "client note",
     *        "terms": "terms"
     *    }
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Invoice Updated Successfully"
     *     }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Invoice Update Fail"
     *     }
     *
     * @apiError {String} number The Invoice number is already in use
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 409 Conflict
     *     {
     *       "status": false,
     *       "error": {
     *            "number":"The Invoice number is already in use"
     *        },
     *        "message": "The Invoice number is already in use"
     *     }
     *
     */
    public function agregarNuevaFactura(array $datosFactura) {
        // Endpoint para agregar una nueva factura.
        $r_factura = json_decode($this->post("invoices", $datosFactura), true);

        /**
         **  Si todo va bien retorna el mensaje de ok y si no el error
         */
        print_r($datosFactura);
        return ($r_factura['status'])?$r_factura['message']:$r_factura['error'];
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
     * @throws Exception
     */
    public function crearClienteYFactura(array $datosCliente, array $datosFactura, array $datosContacto) {

        // Crea el cliente y obtener el ID del cliente o un mensaje de error
        $idCliente = $this->crearCliente($datosCliente);

        if(!empty($datosContacto)){

            $datosContacto["customer_id"] = $idCliente;
            $response = json_decode($this->crearContactoCliente($datosContacto), true);

            // verificamos si el contacto creado no existe
            if(!$response['status']){

               return $response;
            }
        }

        // Verificar si se obtuvo un ID de cliente
        if (is_numeric($idCliente)) {
            // Si se tiene un ID, preparar y enviar la factura para el cliente recién creado
            $datosFactura['clientid'] = $idCliente;
            return $this->agregarNuevaFactura($datosFactura);

        }  else {
            // Si no se obtuvo un ID de cliente, devolver el mensaje de error
            return $datosFactura['clientid']; // Aquí idCliente contiene el mensaje de error
        }
    }

    /**
     * Añade ítems a una estructura de factura existente y calcula el nuevo subtotal y total.
     *
     * Esta función toma una referencia a un array que representa la estructura de datos de una factura
     * y un array que contiene los nuevos ítems a añadir a esta factura. Los ítems pueden incluir un
     * campo especial 'taxname', el cual se maneja de manera diferente a los demás campos, ya que
     * incluye tanto el nombre del impuesto como la tasa aplicable, separados por un '|'. La función
     * modifica directamente el array de la factura, añadiendo los nuevos ítems y recalculando el
     * subtotal y total considerando los impuestos especificados.
     *
     * @param array &$datosFactura Una referencia al array de la factura a modificar. Este array se
     *                             modifica directamente para incluir los nuevos ítems y actualizar
     *                             los valores de 'subtotal' y 'total'.
     * @param array $item El array que contiene los nuevos ítems a añadir a la factura. Cada ítem
     *                    puede tener una clave 'taxname' que indica el impuesto aplicable.
     * @return array El array de la factura modificado con los nuevos ítems añadidos y los valores
     *               de 'subtotal' y 'total' actualizados.
     */
    public function addItemAFactura(array &$datosFactura, array $item): array
    {
        $cuenta_que = 0; // Contador para iterar sobre los nuevos ítems.

        // Itera sobre cada nuevo ítem proporcionado en el argumento $item.
        foreach ($item['newitems'] as $value) {
            // Itera sobre cada campo de un ítem.
            foreach ($value as $eresClave => $valOrate) {
                // Si el campo es 'taxname', lo añade de una manera especial.
                if ($eresClave == 'taxname') {
                    // Añade el valor de 'taxname' a una lista (array) bajo el ítem correspondiente.
                    $datosFactura["newitems[$cuenta_que][$eresClave][]"] = $valOrate;
                } else {
                    // Para todos los otros campos, simplemente asigna el valor al campo correspondiente.
                    $datosFactura["newitems[$cuenta_que][$eresClave]"] = $valOrate;
                }
            }
            $cuenta_que++; // Incrementa el contador para pasar al siguiente ítem.
        }

        $subtotal = 0;
        $impuestosTotales = 0;

        // Recalcula el subtotal y los impuestos para los nuevos ítems.
        foreach ($item['newitems'] as $item) {
            // Calcula el subtotal por ítem multiplicando cantidad por precio unitario.
            $itemSubtotal = $item['qty'] * $item['rate'];
            $subtotal += $itemSubtotal;

            // Si el ítem tiene un 'taxname', extrae la tasa de impuesto y calcula el impuesto.
            if (isset($item['taxname'])) {
                // Separa el nombre del impuesto y la tasa usando explode().
                list($taxName, $taxRate) = explode('|', $item['taxname']);
                $taxRate = (float)$taxRate / 100; // Convierte la tasa a un decimal.

                // Calcula el impuesto para ese ítem.
                $impuestoItem = $itemSubtotal * $taxRate;
                $impuestosTotales += $impuestoItem;
            }
        }

        // Actualiza los valores de 'subtotal' y 'total' en el array de la factura.
        $datosFactura['subtotal'] = number_format($subtotal, 2, '.', '');
        $datosFactura['total'] = number_format($subtotal + $impuestosTotales, 2, '.', '');

        return $datosFactura;
    }


    /**
     * Lista todos los pagos o un pago específico si se proporciona un ID.
     *
     * @param int|null $paymentId ID único del pago (opcional).
     * @return string Respuesta de la API en formato de cadena.
     * @throws Exception
     */
    public function listarPagos(?int $paymentId = null): string {
        $path = "payments";
        if (!is_null($paymentId)) {
            $path .= "/{$paymentId}";
        }

        return $this->get($path);
    }

    /**
     * Añade un nuevo contacto a través de la API.
     *
     * @param array $datosContacto Datos del contacto a añadir.
     * @return string Respuesta de la API en formato de cadena.
     * @throws Exception
     *
     * @api {post} api/contacts/ Add New Contact
     * @apiVersion 0.1.0
     * @apiName PostContact
     * @apiGroup Contacts
     *
     * @apiHeader {String} Authorization Basic Access Authentication token
     *
     * @apiParam {Number} customer_id              Mandatory Customer id.
     * @apiParam {String} firstname                Mandatory First Name
     * @apiParam {String} lastname                 Mandatory Last Name
     * @apiParam {String} email                    Mandatory E-mail
     *
     * @apiParam {String} [title]                  Optional Position
     * @apiParam {String} [phonenumber]            Optional Phone Number
     * @apiParam {String} [direction = 'rtl']       Optional Direction (rtl or ltr)
     * @apiParam {String} [password]                Optional password (only required if you pass send_set_password_email parameter)
     * @apiParam {String} [is_primary = 'on']       Optional Primary Contact (set on or don't pass it)
     * @apiParam {String} [donotsendwelcomeemail]   Optional Do Not Send Welcome Email (set on or don't pass it)
     * @apiParam {String} [send_set_password_email] Optional Send Set Password Email (set on or don't pass it)
     * @apiParam {Array}  [permissions]            Optional Permissions for this contact(["1", "2", "3", "4", "5", "6" ])<br/>
     *                                                            [<br/>
     *                                                                "1",    // Invoices permission<br/>
     *                                                                "2",    // Estimates permission<br/>
     *                                                                "3",    // Contracts permission<br/>
     *                                                                "4",    // Proposals permission<br/>
     *                                                                "5",    // Support permission<br/>
     *                                                                "6"     // Projects permission<br/>
     *                                                            ]
     * @apiParam {String} [invoice_emails = "invoice_emails"]            Optional E-Mail Notification for Invoices (set value same as name or don't pass it)
     * @apiParam {String} [estimate_emails = "estimate_emails"]         Optional E-Mail Notification for Estimate (set value same as name or don't pass it)
     * @apiParam {String} [credit_note_emails = "credit_note_emails"]   Optional E-Mail Notification for Credit Note (set value same as name or don't pass it)
     * @apiParam {String} [project_emails = "project_emails"]            Optional E-Mail Notification for Project (set value same as name or don't pass it)
     * @apiParam {String} [ticket_emails = "ticket_emails"]            Optional E-Mail Notification for Tickets (set value same as name or don't pass it)
     * @apiParam {String} [task_emails = "task_emails"]                Optional E-Mail Notification for Task (set value same as name or don't pass it)
     * @apiParam {String} [contract_emails ="contract_emails"]            Optional E-Mail Notification for Contract (set value same as name or don't pass it)
     *
     * @apiSuccess {Boolean} status Request status.
     * @apiSuccess {String} message Contact added successfully.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "message": "Contact added successfully"
     *     }
     *
     * @apiError {Boolean} status Request status
     * @apiError {String} message Contact add fail
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "status": false,
     *       "message": "Contact add fail"
     *     }
     *
     * @apiError {String} email This Email is already exists
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 409 Conflict
     *     {
     *       "status": false,
     *       "error": {
     *            "email":"This Email is already exists"
     *        },
     *        "message": "This Email is already exists"
     *     }
     */
    public function crearContactoCliente(array $datosContacto, $primario = 0): string {

        // Definimos si el contacto de cliente es primario o no
        if($primario){
            $datosContacto['is_primary'] = 'on';
        }
        return $this->post("contacts/", $datosContacto,);
    }

    /**
     * Solicita la información de los impuestos.
     *
     * @return string Respuesta de la API.
     * @throws Exception
     */
    public function solicitarDatosImpuestos(): string {
        return $this->get("common/tax_data");
    }

    /**
     * @throws Exception
     */
    public function get($path, array $headers = []): string {
        return $this->request('GET', $path, null, $headers);
    }

    /**
     * @throws Exception
     */
    public function post($path, $data, array $headers = []): string {
        return $this->request('POST', $path,  $data, $headers);
    }

    /**
     * @throws Exception
     */
    public function put($path, $data, array $headers = []): string {
        return $this->request('PUT', $path, $data, $headers);
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
     * @param mixed|null $data Datos a enviar con la solicitud.
     * @param array $headers Headers adicionales para la solicitud.
     * @return string Respuesta de la API.
     * @throws Exception
     */
    private function request(string $method, string $path,  $data = null, array $headers = []): string {

        $headers = $this->addAuthorizationHeader($headers);

        curl_setopt_array($this->curl, [
            CURLOPT_URL => $this->base_url . $path,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data
        ]);

        $response = curl_exec($this->curl);
        if ($response === false) {
            throw new Exception(curl_error($this->curl), curl_errno($this->curl));
        }

        return $response;
    }
    private function addAuthorizationHeader(array $headers): array {
        $headers[] = 'authtoken: '.$this->authtoken;
        return $headers;
    }
    public function __destruct() {
        curl_close($this->curl);
    }
}
