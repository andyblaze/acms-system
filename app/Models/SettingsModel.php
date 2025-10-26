<?php 
namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model {
    protected $table = 'site_settings';
    protected $returnType = 'object';

    public function getSettings(): array {
        $settings = [];
        foreach ($this->findAll() as $row) {
            $settings[$row->key] = $row->value;
        }
        return $settings;
    }
}