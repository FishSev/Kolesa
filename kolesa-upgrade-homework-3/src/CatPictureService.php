<?php

namespace cat;

use GuzzleHttp\Client;

class CatPictureService
{
    private $client;

    function initializeClient(): void
    {
        $client = new Client([
            'base_uri' => 'https://api.thecatapi.com/',
            'timeout' => 2.0,
            'verify' => false,
        ]);
        $this->client = $client;
    }

    function outputCategoryList(): void
    {
        $response = $this->client->get(uri: 'v1/categories');
        $result = json_decode($response->getBody()->getContents(), associative: true);
        asort($result);
        foreach ($result as $category) {
            echo $category["id"] . ' - ' . $category["name"] . PHP_EOL . '<br>';
        }
    }

    function getPicUrl(): array
    {
        $category = $_GET['category_ids'] ?? null;
        if (!isset($category)) {
            $additionalUri = 'v1/images/search';
        } else {
            $additionalUri = 'v1/images/search?category_ids=' . $category;
        }
        try {
            $response = $this->client->get(uri: $additionalUri);
            $result = json_decode($response->getBody()->getContents(), associative: true);
            if (empty($result)) {
                return ["Неправильная категория", '<h1>Такой категории не существует. Попробуйте другой запрос.<h1>'];
            } else {
                return ["success", $result[0]['url']];
            }
        } catch (\Exception $error) {
            return [$error->getMessage(), '<h1>Проблемы с доступом к ресурсу. Повторите попытку позднее либо попробуйте другой запрос.<h1>'];
        }
    }

    public function outputCat(): void
    {
        $resultUrlOrErr = $this->getPicUrl();
        if ($resultUrlOrErr[0] != "success") {
            echo $resultUrlOrErr[1];
        } else {
            echo '<h1>Привет, вот твой рандомный котик</h1><br>';
            echo '<img src="' . $resultUrlOrErr[1] . '" width="500" height="400" />';
        }
    }
}