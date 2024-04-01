<?php

class ApiWonline {

    private $base_url = 'url';
    private $authtoken = 'eyJ0eX';
    private $curl;

    public function __construct() {
        $this->curl = curl_init();
    }

    public function get($path, array $headers = []): string {
        $headers = $this->addAuthorizationHeader($headers);
        curl_setopt($this->curl, CURLOPT_URL, $this->base_url . $path);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($this->curl);
        return $response;
    }

    public function post($path, $data, array $headers = []): string {
        $headers = $this->addAuthorizationHeader($headers);
        curl_setopt($this->curl, CURLOPT_URL, $this->base_url . $path);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($this->curl);
        return $response;
    }

    public function put($path, $data, array $headers = []): string {
        $headers = $this->addAuthorizationHeader($headers);
        curl_setopt($this->curl, CURLOPT_URL, $this->base_url . $path);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($this->curl);
        return $response;
    }

    private function addAuthorizationHeader(array $headers): array {
        if (!empty($this->authtoken)) {
            $headers[] = 'authtoken: '.$this->authtoken;
        }
        return $headers;
    }

    public function __destruct() {
        curl_close($this->curl);
    }
}