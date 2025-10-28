<?php

namespace App\Models;

use App\Models\CmsBaseModel;

class PageTemplatesModel extends CmsBaseModel {
    protected $table = 'page_templates';
    protected $valueField = 'view_file';
}