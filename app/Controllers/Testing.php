<?php

namespace App\Controllers;

class Testing extends BaseController {
    public function index(): string {
        return view('media_manager');
    }
}
