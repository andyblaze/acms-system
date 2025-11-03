<?php 
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class Content extends ResourceController {
    public function index() {

    }
    public function save() {
        var_dump($this->request->getPost());
    }
}