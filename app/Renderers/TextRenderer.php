<?php
namespace App\Renderers;

class TextRenderer {
    protected $data = null;
    public function __construct($d) {
        $this->data = $d;
    }
    public function render() {
        return $this->data->content;
    }
}