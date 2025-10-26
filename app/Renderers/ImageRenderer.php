<?php
namespace App\Renderers;

class ImageRenderer {
    protected $data = null;
    public function __construct($d) {
        $this->data = $d;
    }
    public function render() {
        return img($this->data->path);
    }
}