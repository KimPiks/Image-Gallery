<?php

namespace repositories;

use Database;

require_once ("../Database.php");

class ImageRepository
{
    private $db;
    private $userRepository;

    public function __construct()
    {
        $this->db = (new Database())->get();
    }

    public function add($image)
    {
        $result = $this->db->images->insertOne($image);
        if ($result->getInsertedCount() == 0)
        {
            throw new \Exception("Error adding file to database");
        }
        return $result->getInsertedId();
    }

    public function getAll()
    {
        return $this->db->images->find();
    }

    public function getById($id)
    {
        return $this->db->images->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
    }

    public function getByPhrase($phrase)
    {
        return $this->db->images->find(["title" => new \MongoDB\BSON\Regex('^' . $phrase, 'i')]);
    }

    public function remove($id)
    {
        $this->db->images->deleteOne(['_id' => $id]);
    }
}