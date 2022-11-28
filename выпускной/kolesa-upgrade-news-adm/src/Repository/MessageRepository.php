<?php

namespace App\Repository;

use App\Model\Entity\Message;
use App\Repository\DB\Database;

class MessageRepository
{
    private \PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection()->getPdo();
    }

    public function create(array $data): Message
    {
        $statement = $this->connection->prepare('INSERT INTO messages(title,body) VALUES (:title, :body)');
        $statement->bindParam('title', $data['title']);
        $statement->bindParam('body', $data['body']);
        $statement->execute();

        return new Message($data);
    }

    /**
     * @return Message[]
     */
    public function getAll(): array
    {
        $messages = $this
            ->connection
            ->query('SELECT * FROM messages')
            ->fetchAll(\PDO::FETCH_ASSOC);

        if (!$messages) {
            return [];
        }

        $result = [];

        foreach ($messages as $messageData) {
            $result[] = new Message($messageData);
        }

        return $result;
    }
}