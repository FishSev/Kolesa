<?php

namespace App\Model\Repository;

use App\Model\Entity\Advert;

class AdvertRepository
{
    private const DB_PATH = '../storage/adverts.json';

    public function getAll()
    {
        $result = [];

        foreach ($this->getDB() as $advertData) {
            $result[] = new Advert($advertData);
        }

        return $result;
    }

    public function create(array $advertData): Advert
    {
        $db               = $this->getDB();
        $increment        = array_key_last($db) + 1;
        $advertData['id'] = $increment;
        $db[$increment]   = $advertData;

        $this->saveDB($db);

        return new Advert($advertData);
    }

    private function getDB(): array
    {
        return json_decode(file_get_contents(self::DB_PATH), true) ?? [];
    }

    private function saveDB(array $data): void
    {
        file_put_contents(self::DB_PATH, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    public function getAdvertById(int $id): Advert
    {
        $arr = $this->getDB();
        for ($i = 1; $i <= count($arr); $i++) {
            if ($arr[$i] == null) {
                continue;
            }
            if ($arr[$i]['id'] == $id) {
                $advertData = $arr[$i];
                return new Advert($advertData);
            }
        }
    }

    public function editAdvert(array $advertData, $id): Advert
    {
        $db = $this->getDB();
        $advertData['id'] = $id;
        $db[$id]   = $advertData;
        $this->saveDB($db);
        return new Advert($advertData);
    }
}
