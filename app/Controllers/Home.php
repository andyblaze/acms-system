<?php

namespace App\Controllers;

class Home extends BaseController {
    public function index(): string {
        return view('welcome_message', ['page_title'=>'CI4 CMS', 'meta_description'=>'The small framework with powerful features']);
    }
}
