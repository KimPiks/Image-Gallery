<?php

class Database
{
    function get()
    {
        $mongo = new MongoDB\Client(
            "mongodb://localhost:27017/wai",
            [
                'username' => 'wai_web',
                'password' => 'w@i_web',
            ]);

        $db = $mongo->wai;

        return $db;
    }
}