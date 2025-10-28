<?php

namespace App\Controllers;
use App\Models\PagesModel;
use App\Models\HtmlIdentifiersModel;
use App\Models\PageTemplatesModel;
use App\Models\RendererClassesModel;
use App\Libraries\FormBuilder;

class Home extends BaseController {
    protected function buildSelects() {
        $fields = [
            'page_id'               => PagesModel::class,
            'html_identifier_id'    => HtmlIdentifiersModel::class,
            'template_id'           => PageTemplatesModel::class,
            'renderer_id'           =>RendererClassesModel::class
        ];

        $f = new FormBuilder();
        $f->open();

        // loop through the fields
        foreach ( $fields as $field => $modelClass ) {
            $m = new $modelClass();
            $f->select($field, $m->asIdValueMap());
        }
        return $f->close();
    }
    public function index(): string {
        //$f->select('status', $m->enumValues());
        echo $this->buildSelects();
        return view('pages/' . $this->viewData['view_file'], $this->viewData);
    }
}
