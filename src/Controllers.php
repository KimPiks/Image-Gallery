<?php

use services\AuthService;
use services\ImageService;
use repositories\UserRepository;
use repositories\ImageRepository;

require_once('services/AuthService.php');
require_once('services/ImageService.php');
require_once('repositories/UserRepository.php');
require_once('repositories/ImageRepository.php');

class Controllers
{
    private $authService;
    private $userRepository;
    private $imageRepository;
    private $imageService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->imageService = new ImageService();
        $this->userRepository = new UserRepository();
        $this->imageRepository = new ImageRepository();
    }

    function pageNotFound(&$model)
    {
        return 'page-not-found';
    }

    function gallery(&$model)
    {
        foreach ($_POST as $key => $value)
        {
            if (substr($key, 0, 6) == 'check-')
            {
                $id = substr($key, 6);
                $_SESSION['saved_images'][] = $id;
            }
        }

        $model['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
        if ($model['page'] < 1)
        {
            $model['page'] = 1;
        }

        $model['images'] = $this->imageService->get($model['page']);
        $model['nextPageImages'] = $this->imageService->get($model['page'] + 1);

        if ($model['nextPageImages'] == null ||
            $model['nextPageImages'][0]['_id'] == $model['images'][0]['_id'])
        {
            $model['nextPageImages'] = null;
        }

        if ($model['images'] == null && $model['page'] > 1)
        {
            header('Location: /gallery');
        }

        return 'gallery';
    }

    function savedImages(&$model)
    {
        foreach ($_POST as $key => $value)
        {
            if (substr($key, 0, 6) == 'check-')
            {
                $id = substr($key, 6);
                $key = array_search($id, $_SESSION['saved_images']);
                unset($_SESSION['saved_images'][$key]);
            }
        }

        $savedImages = array();

        if (isset($_SESSION['saved_images']))
        {
            foreach ((array)$_SESSION['saved_images'] as $image)
            {
                $savedImages[] = $this->imageRepository->getById($image);
            }
        }

        if (count($savedImages) > 0)
        {
            $model['saved_images'] = $savedImages;
        }

        return 'saved-images';
    }

    function addImage(&$model)
    {
        if (isset($_FILES['image']) &&
            isset($_POST['title']) &&
            isset($_POST['watermark']) &&
            isset($_POST['author']))
        {
            $private = false;
            if (isset($_POST['private']))
            {
                $private = true;
            }

            try
            {
                $this->imageService->upload($_FILES['image'], $_POST['title'], $_POST['watermark'], $_POST['author'], $private);
                header('Location: /gallery');
            }
            catch (Exception $e)
            {
                $model['error-message'] = $e->getMessage();
            }
        }

        if (isset($_SESSION["user_id"]))
        {
            $model['user'] = $this->userRepository->getById($_SESSION["user_id"]);
            $model['username'] = $model['user']["login"];
        }

        return 'add-image';
    }

    function image(&$model)
    {
        try
        {
            $model['image'] = $this->imageRepository->getById($_GET['id']);
            $model['imageFound'] = true;
            $model['canView'] = true;

            if ($model['image'] == null)
            {
                $model['imageFound'] = false;
            }
            if (!$model['image']['public'])
            {
                if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $model['image']['authorId'])
                {
                    $model['canView'] = false;
                }
            }
        }
        catch (Exception $e)
        {
            $model['imageFound'] = false;
        }

        return 'image';
    }

    function search(&$model)
    {
        return 'search';
    }

    function searchAjax(&$model)
    {
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            header('Location: page-not-found');
        }

        $images = $this->imageRepository->getByPhrase($_GET['phrase']);
        echo '[';
        $i = 0;
        foreach ($images as $image)
        {
            if ($image['public'] == false && (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $image['authorId']))
            {
                continue;
            }
            if ($i != 0)
            {
                echo ',';
            }
            echo json_encode($image);
            $i++;
        }
        echo ']';
        return 'search-ajax';
    }

    function login(&$model)
    {
        if (isset($_SESSION["user_id"]))
        {
            header('Location: /gallery');
            return 'gallery';
        }
        if (isset($_POST['login']) && isset($_POST['password']))
        {
            if ($this->authService->login($_POST['login'], $_POST['password']))
            {
                header('Location: /gallery');
                return 'gallery';
            }
            else
            {
                $model['error-message'] = "Invalid login or password";
            }
        }
        return 'login';
    }

    function register(&$model)
    {
        if (isset($_SESSION["user_id"]))
        {
            header('Location: /gallery');
            return 'gallery';
        }
        if (isset($_POST['login']) && isset($_POST['email']) && isset($_POST['password']))
        {
            try
            {
                $this->authService->register($_POST['login'], $_POST['email'], $_POST['password']);
                header('Location: /login');
                return 'login';
            }
            catch (Exception $e)
            {
                $model['error-message'] = $e->getMessage();
            }
        }
        return 'register';
    }

    function logout(&$model)
    {
        if (!isset($_SESSION["user_id"]))
            return 'gallery';

        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');

        header ('Location: /gallery');
        return 'gallery';
    }
}