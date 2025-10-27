<?php

namespace App\Controllers;

class Home extends BaseController {
    public function index(): string {
        return view('pages/' . $this->viewData['view_file'], $this->viewData);
    }
}
