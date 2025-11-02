<?php

namespace App\Models;

use App\Models\CmsBaseModel;

class MediaContentModel extends CmsBaseModel {
    protected $table = 'media_content';
    protected $valueField = 'path';
    protected $enumField = 'type';
    
}