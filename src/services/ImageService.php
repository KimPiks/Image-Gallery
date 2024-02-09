<?php

namespace services;

use repositories\ImageRepository;
use repositories\UserRepository;

require_once ("../repositories/ImageRepository.php");
require_once ("../repositories/UserRepository.php");

const __UPLOAD_DIR__ = "images/";

class ImageService
{
    private $imageRepository;
    private $userRepository;

    public function __construct()
    {
        $this->imageRepository = new ImageRepository();
        $this->userRepository = new UserRepository();
    }

    public function upload($image, $title, $watermark, $author, $private)
    {
        if ($image['size'] > 1024 * 1024 || $image['error'] == 1)
        {
            throw new \Exception("File is too big (max 1MB)");
        }
        if ($image['type'] != 'image/png' && $image['type'] != 'image/jpeg')
        {
            throw new \Exception("Invalid file type (only png and jpeg allowed)");
        }
        if ($private == 'on' && !isset($_SESSION['user_id']))
        {
            throw new \Exception("You must be logged in to upload private images");
        }
        if ($image['error'] != 0)
        {
            throw new \Exception("Error uploading file - " . $image['error']);
        }

        $type = "png";
        if ($image['type'] == 'image/jpeg')
        {
            $type = "jpg";
        }

        try
        {
            $id = $this->imageRepository->add($this->prepareImageModel($title, $private, $type, $author));

            if (!move_uploaded_file($image['tmp_name'], $this->getUploadDir($id, $type)))
            {
                $this->imageRepository->remove($id);
                throw new \Exception("Error uploading file");
            }
        }
        catch (\Exception $e)
        {
            throw $e;
        }

        $this->createImageWithWatermark($id, $type, $watermark);
        $this->createMiniImage($id, $type);
    }

    public function get($page)
    {
        $images = $this->imageRepository->getAll();
        $imagesToView = [];

        foreach ($images as $image)
        {
            if ($image['public'])
            {
                $imagesToView[] = $image;
            }
            else if (isset($_SESSION['user_id']) && $image['authorId'] == $_SESSION['user_id'])
            {
                $imagesToView[] = $image;
            }

            if (count($imagesToView) == 3)
            {
                if ($page > 1)
                {
                    $imagesToView = [];
                    $page--;
                    continue;
                }
                break;
            }
        }

        return $imagesToView;
    }

    private function createMiniImage($imageName, $imageExtension)
    {
        $image = $this->createImage($imageName, $imageExtension);
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);
        $mini = imagecreatetruecolor(200, 125);
        imagecopyresampled($mini, $image, 0, 0, 0, 0, 200, 125, $originalWidth, $originalHeight);

        $this->saveImage($mini, $imageName . "-mini", $imageExtension);

        imagedestroy($image);
        imagedestroy($mini);
    }

    private function createImageWithWatermark($imageName, $imageExtension, $watermark)
    {
        $image = $this->createImage($imageName, $imageExtension);
        $positionX = imagesx($image) / 4;
        $positionY = imagesy($image);

        $color = imagecolorallocatealpha($image, 0, 0, 0, 50);

        imagettftext($image, imagesx($image) / 6.67, 45, $positionX, $positionY, $color, "static/fonts/NotoSans-Regular.ttf", $watermark);
        $this->saveImage($image, $imageName . "-watermark", $imageExtension);
        imagedestroy($image);
    }

    private function createImage($imageName, $imageExtension)
    {
        if ($imageExtension == "jpg")
            return imagecreatefromjpeg($this->getUploadDir($imageName, $imageExtension));
        else
            return imagecreatefrompng($this->getUploadDir($imageName, $imageExtension));
    }

    private function saveImage($image, $imageName, $imageExtension)
    {
        if ($imageExtension == "jpg")
            imagejpeg($image, $this->getUploadDir($imageName, $imageExtension));
        else
            imagepng($image, $this->getUploadDir($imageName, $imageExtension));
    }

    private function prepareImageModel($title, $private, $type, $author)
    {
        $image = [
            'title' => $title,
            'public' => !($private == 'on'),
            'type' => $type,
            'author' => $author,
            'authorId' => null
        ];

        if (isset($_SESSION['user_id']))
        {
            $user = $this->userRepository->getById($_SESSION['user_id']);
            $image['authorId'] = $_SESSION['user_id'];
            $image['author'] = $user['login'];
        }

        return $image;
    }

    private function getUploadDir($fileName, $extension)
    {
        return __UPLOAD_DIR__ . $fileName . "." . $extension;
    }
}