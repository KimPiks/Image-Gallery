<?php

namespace repositories;

use Database;

require_once ("../Database.php");

class UserRepository
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->get();
    }

    public function add($user)
    {
        $result = $this->db->users->insertOne($user);
        if ($result->getInsertedCount() == 0)
        {
            throw new \Exception("Error adding user to database");
        }
        return $result->getInsertedId();
    }

    public function getByLogin($login)
    {
        return $this->db->users->findOne(['login' => $login]);
    }

    public function getById($id)
    {
        return $this->db->users->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
    }
}