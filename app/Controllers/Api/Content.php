<?php 
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class Content extends ResourceController {
    public function index() {

    }
    public function save() {
        $security = \Config\Services::security();
        $data = $this->request->getPost();

        foreach ($data as $key => $value) {
            if ( is_numeric($value) ) {
                $data[$key] = ctype_digit($value) ? (int)$value : (float)$value;
            } elseif ( is_string($value) ) {
                $data[$key] = esc($value);
            }
        }
        var_dump($data);
    }
}