<?php

class Routing
{
    private $routing = [
        "/" => 'gallery',
        '/gallery' => 'gallery',
        '/login' => 'login',
        '/register' => 'register',
        '/logout' => 'logout',
        '/add-image' => 'addImage',
        '/search' => 'search',
        '/image' => 'image',
        '/saved-images' => 'savedImages',
        '/search-ajax' => 'searchAjax',
    ];

    public function getRouting($name)
    {
        if (isset($this->routing[$name])) {
            return $this->routing[$name];
        } else {
            return 'pageNotFound';
        }
    }
}