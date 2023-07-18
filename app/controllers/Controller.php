<?php

namespace App\controllers;

class Controller
{
    public string $route;
    public object $model;
    public int $id;

    public function __construct($route, $id = null)
    {
        $this->route = $route;
        $this->id = $id;
        $this->model = $this->loadModel($route);
    }

    public function loadModel($route): object|null
    {
        $route = explode('/', $route);
        $path = 'app\models\\' . ucfirst($route[0]);
        if (class_exists($path)) {
            return new $path();
        }
        return null;
    }
}
