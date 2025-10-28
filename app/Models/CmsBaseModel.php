<?php

namespace App\Models;

use CodeIgniter\Model;

class CmsBaseModel extends Model {
    protected $table            = '';        // override in subclass
    /*protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;*/

    protected $returnType       = 'object';

    protected $allowedFields    = [];         // override in subclass

    protected $useTimestamps    = false;
    /*protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = true;*/
    
    protected $valueField = '';
    protected $enumField = '';

    /**
     * Returns the last inserted ID (after insert)
     */
    public function lastInsertedID(): ?int {
        return $this->db->insertID();
    }
    public function tableName() {
        return $this->table;
    }
    public function asIdValueMap($vf=null):array {
        $valueField = $vf === null ? $this->valueField : $vf;
        $map = [];
        $rows = $this->findAll();
        foreach ( $rows as $row ) {
            $map[$row->{$this->primaryKey}] = $row->{$valueField};
        }
        return $map;
    }
    public function enumValues($ef=null) {
        $enumField = $ef === null ? $this->enumField : $ef;
        return [];
    }
}