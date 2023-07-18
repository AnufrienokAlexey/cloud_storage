<?php

namespace App\controllers;

use App\controllers;
use App\core\View;

class AboutMeController extends Controller
{
    public function about_me()
    {
        $this->view->render('Обо мне');
    }
}