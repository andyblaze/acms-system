<?php

namespace App\Models;

use App\Models\CmsBaseModel;

class TextContentModel extends CmsBaseModel {
    protected $table = 'text_content';
    protected $valueField = 'content';
    protected $enumField = 'format';
    
}