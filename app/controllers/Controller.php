<?php

namespace App\controllers;

use App\core\View;

class Controller
{
    public $route;
    public $view;
    public $model;

    public function __construct($route)
    {
        $this->route = $route;
        $this->view = new View($route);
        $this->model = $this->loadModel($route);
    }

    public function loadModel($name)
    {
        $name = explode('/', $name);
        $path = 'app\models\\'.ucfirst($name[0]);
        debug($path);
        if (class_exists($path)) {
            return new $path;
        }
    }
}