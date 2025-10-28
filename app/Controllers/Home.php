<?php

namespace App\Controllers;
use App\Models\PagesModel;
use App\Models\HtmlIdentifiersModel;
use App\Models\PageTemplatesModel;
use App\Libraries\FormBuilder;

class Home extends BaseController {
    public function index(): string {
        $f = new FormBuilder();
        $m = new PagesModel();
        $f->open();
        $f->select('page_id', $m->asIdValueMap());
        $f->select('status', $m->enumValues());
        $m = new HtmlIdentifiersModel();
        $f->select('html_identifier_id', $m->asIdValueMap());
        $m = new PageTemplatesModel();
        $f->select('template_id', $m->asIdValueMap());
        echo $f->close();
        return view('pages/' . $this->viewData['view_file'], $this->viewData);
    }
}
