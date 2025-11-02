<?php

namespace App\Models;

use App\Models\CmsBaseModel;

class ContentMapModel extends CmsBaseModel {
    protected $table = 'content_map';
    //protected $valueField = 'html_identifier';
    protected $enumField = 'content_type';
}