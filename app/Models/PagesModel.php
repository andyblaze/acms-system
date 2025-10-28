<?php

namespace App\Models;

use App\Models\CmsBaseModel;

class PagesModel extends CmsBaseModel {
    protected $table = 'pages';
    protected $valueField = 'url';
    protected $enumField = '';
    
    public function enumValues($ef=null) {
        $enumField = $ef === null ? $this->enumField : $ef;
        return [
            'published'=>'published',
            'draft'=>'draft'
        ];
    }
}