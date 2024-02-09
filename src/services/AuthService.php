<?php

namespace services;

use repositories\UserRepository;

require_once('../repositories/UserRepository.php');

class AuthService
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login($login, $password)
    {
        $user = $this->userRepository->getByLogin($login);
        if ($user && password_verify($password, $user["password"]))
        {
            session_unset();
            session_regenerate_id();
            $_SESSION["user_id"] = $user["_id"];
            return true;
        }
        return false;
    }

    public function register($login, $email, $password)
    {
        $user = $this->userRepository->getByLogin($login);
        if ($user)
        {
            throw new \Exception("User with given login already exists");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw new \Exception("Invalid email");
        }

        $this->userRepository->add($this->prepareUser($login, $email, $password));
    }

    private function prepareUser($login, $email, $password)
    {
        return [
            'login' => $login,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ];
    }
}