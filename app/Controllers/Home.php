<?php

namespace App\Controllers;
use App\Models\PagesModel;
use App\Models\HtmlIdentifiersModel;
use App\Models\PageTemplatesModel;
use App\Libraries\FormBuilder;

class Home extends BaseController {
    public function index(): string {
        $m = new PageTemplatesModel();
        $f = new FormBuilder();
        echo $f->open()->select('page_id', $m->asIdValueMap())->close();
        return view('pages/' . $this->viewData['view_file'], $this->viewData);
    }
}
