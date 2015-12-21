<?php

namespace App;

class AdminController extends Controller {

    public function index() {
        $this->app->render('admin/layout.twig');
    }
}