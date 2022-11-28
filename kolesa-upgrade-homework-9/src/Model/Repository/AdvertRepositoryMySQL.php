<?php

namespace App\Model\Repository;

use App\Model\Entity\Advert;
use App\Model\DB\DB;
use \PDO;

class AdvertRepositoryMySQL
{
    private const DB_PATH = '../storage/adverts.json';

    public function getAll()
    {
        $result = [];
        foreach ($this->getDB() as $advert) {
            $result[] = new Advert($advert);
        }
        return $result;
    }

    public function create(array $advertData): Advert
    {
        $db               = $this->getDB();
        $increment        = array_key_last($db) + 1;
        $advertData['id'] = $increment;
        $db[$increment]   = $advertData;
        $this->appendDB($advertData);
        return new Advert($advertData);
    }

    private function getDB(): array
    {
        $DB = new DB();
        $pdo = $DB->getConnection();
        $stmt = $pdo->query("SELECT * FROM adverts");
        $adverts = $stmt->fetchAll(mode: PDO::FETCH_ASSOC);

        return $adverts ?? [];
    }

    private function appendDB(array $advertData): void
    {
        $DB = new DB();
        $pdo = $DB->getConnection();

        $sql = "INSERT INTO adverts (title, description, price)
        VALUES (:title, :description, :price)";
        $query = $pdo->prepare($sql);
        $query -> bindParam(':title', $advertData['title'], PDO::PARAM_STR);
        $query -> bindParam(':description' , $advertData['description'] , PDO::PARAM_STR);
        $query -> bindParam(':price' , $advertData['price'] , PDO::PARAM_INT);
        $query -> execute();
    }

    private function updateDB(array $advertData, $id): void
    {
        $DB = new DB();
        $pdo = $DB->getConnection();

        $sql = "UPDATE adverts
        SET `title`= :title, `description` = :description, `price` = :price
        WHERE `id` = :id";
        $query = $pdo->prepare($sql);
        $query -> bindParam(':title', $advertData['title'], PDO::PARAM_STR);
        $query -> bindParam(':description' , $advertData['description'] , PDO::PARAM_STR);
        $query -> bindParam(':price' , $advertData['price'] , PDO::PARAM_INT);
        $query -> bindParam(':id' , $id , PDO::PARAM_INT);
        $query -> execute();
    }

    public function getAdvertById(int $id): Advert
    {
        $adverts = $this->getDB();
        foreach ($adverts as $advert) {
            if ($advert['id'] == $id) {
                return new Advert($advert);
            }
        }
    }

    public function editAdvert(array $advertData, $id): Advert
    {
        $db = $this->getDB();
        $advertData['id'] = $id;
        $db[$id]   = $advertData;
        $this->updateDB($advertData, $id);
        return new Advert($advertData);
    }
}
