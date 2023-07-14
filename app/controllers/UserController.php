<?php

namespace App\controllers;

use app\controllers;

class UserController extends Controller
{
    public function list()
    {
        debug($this->model->getUsers());
        $this->model->getUsers();
        $this->view->render('getUsers');
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