<?php

namespace App\controllers;

use app\controllers;
use App\core\View;

class UserController extends Controller
{
    public function list()
    {
        $this->view->render('Страница обо мне', $this->model->usersList());
    }

    public function get()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->view->render('Страница обо мне', $this->model->usersGet($id));
            return;
        }
        $this->view->render('Страница обо мне', $this->model->usersList());
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