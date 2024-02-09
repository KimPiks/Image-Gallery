<?php
require_once '../Routing.php';
require_once '../Dispatcher.php';
require_once '../Controllers.php';

class FrontController
{
    private $routing;
    private $dispatcher;

    public function __construct()
    {
        $this->routing = new Routing();
        $this->dispatcher = new Dispatcher();

        session_start();

        $this->route();
    }

    private function route()
    {
        $action_url = $_GET['action'];
        $this->dispatcher->dispatch($this->routing, $action_url);
    }
}