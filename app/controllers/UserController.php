<?php

namespace App\controllers;

use app\controllers;

class UserController extends Controller
{
    public function list()
    {
        $this->view->render('Страница обо мне', $this->model->getUsers());
    }

    public function get()
    {
        echo 'get()';
    }

    public function update()
    {
        echo 'update()';
    }

    public function login()
    {
        echo 'login()';
    }

    public function logout()
    {
        session_destroy();
        $this->view->redirect('/');
    }

    public function reset_password()
    {
        echo 'reset_password()';
    }
}