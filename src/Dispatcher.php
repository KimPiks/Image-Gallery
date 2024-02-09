<?php

const REDIRECT_PREFIX = 'redirect:';

class Dispatcher
{
    private $controllers;

    public function __construct()
    {
        $this->controllers = new Controllers();
    }

    public function dispatch($routing, $action_url)
    {
        $controller_name = $routing->getRouting($action_url);

        $model = [];
        $view_name = $this->controllers->$controller_name($model);

        $this->build_response($view_name, $model);
    }

    private function build_response($view, $model)
    {
        if (strpos($view, REDIRECT_PREFIX) === 0) {
            $url = substr($view, strlen(REDIRECT_PREFIX));
            header("Location: " . $url);
            exit;
        } else {
            $this->render($view, $model);
        }
    }

    private function render($view_name, $model)
    {
        global $routing;
        extract($model);
        include 'views/' . $view_name . '.php';
    }

}