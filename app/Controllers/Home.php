<?php

namespace App\Controllers;
use App\Models\PagesModel;
use App\Models\HtmlIdentifiersModel;
use App\Models\PageTemplatesModel;
use App\Models\RendererClassesModel;
use App\Libraries\FormBuilder;
use App\Libraries\AttributesManager;


class Home extends BaseController {
    protected function buildSelects() {
        helper('inflector');
        $fields = [
            'page_id'               => PagesModel::class,
            'html_identifier_id'    => HtmlIdentifiersModel::class,
            'template_id'           => PageTemplatesModel::class,
            'renderer_id'           =>RendererClassesModel::class
        ];

        $f = new FormBuilder(new AttributesManager());
        $f->open();

        // loop through the fields
        foreach ( $fields as $field => $modelClass ) {
            $m = new $modelClass();
            $f->label(humanize($field))->select($field, $m->asIdValueMap());
        }
        $f->label('Text content')->input('text_content');
        $f->label('Html content')->textarea('html_content');
        return $f->close();
    }
    public function index(): string {
        //$f->select('status', $m->enumValues());
        echo $this->buildSelects();
        return view('pages/' . $this->viewData['view_file'], $this->viewData);
    }
}
