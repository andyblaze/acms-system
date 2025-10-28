<?php

namespace App\Models;

use App\Models\CmsBaseModel;

class PagesModel extends CmsBaseModel {
    protected $table = 'pages';
    protected $valueField = 'url';
}