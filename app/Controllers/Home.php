<?php

namespace App\Controllers;

class Home extends BaseController {
    public function index(): string {
        $this->viewData += ['meta_description'=>'A CMS'];
        return view('pages/' . $this->viewData['view_file'], $this->viewData);
    }
}
