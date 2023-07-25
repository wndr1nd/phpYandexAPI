<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class YandexDiskAPI
{
    private string $base_url = 'https://cloud-api.yandex.net/v1/disk';
    private string $token;

    public function __construct($token)
    {
        $this->token = $token;
    }


    public function makeRequest($method, $endpoint, $params = []) {

        $client = new Client();
        $url = $this->base_url . $endpoint;
        $options = [
            'headers' => [
                'Authorization' => 'OAuth ' . $this->token,
                'Content-Type' => 'application/json'
            ],

            "query" => $params,
        ];


        try {
            $response = $client->request($method, $url, $options);

            return json_decode($response->getBody(), true);

        } catch (ClientException $e) {

            return ['error' => $e->getMessage()];
        }
    }



    public function createFolder($folderName) {
        $endpoint = '/resources/';

        $params = [
            "path" => $folderName
        ];

        return $this->makeRequest("PUT", $endpoint, $params);
    }

    public function uploadFile($filePath, $folderPath)
    {
        $endpoint = "/resources/upload/";

        $params = [
            "url" => $filePath,
            "path" => $folderPath
        ];

        return $this->makeRequest("PUT", $endpoint, $params);

    }
}

$accessToken = '';  //токен для авторизации

$diskAPI = new YandexDiskAPI($accessToken);

$response = $diskAPI->createFolder("exFolder");     //создание папки в корне яндекс диска

$response = $diskAPI->uploadFile("", "");     //загрузка файла(filePath) на яд(folderPath)
print_r($response);
