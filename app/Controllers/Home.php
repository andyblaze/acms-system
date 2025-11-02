<?php

namespace App\Controllers;
use App\Models\PagesModel;
use App\Models\HtmlIdentifiersModel;
use App\Models\PageTemplatesModel;
use App\Models\RendererClassesModel;
use App\Models\ContentMapModel;
use App\Libraries\FormBuilder;
use App\Libraries\AttributesManager;


class Home extends BaseController {
    protected function buildSelects($frm) {
        helper('inflector');
        $fields = [
            'page_id'               => PagesModel::class,
            'html_identifier_id'    => HtmlIdentifiersModel::class,
            'template_id'           => PageTemplatesModel::class,
            'renderer_id'           => RendererClassesModel::class
        ];

        // loop through the fields
        foreach ( $fields as $field => $modelClass ) {
            $m = new $modelClass();
            $frm->label(humanize($field))->select($field, $m->asIdValueMap());
        }
        $frm->label('Text content')->input('text_content');
        $frm->label('Html content')->textarea('html_content');
        //return $f->close();
    }
    public function index(): string {
        $frm = new FormBuilder(new AttributesManager());
        $frm->open();

        $m = new ContentMapModel();
        $frm->label('Content type')->select('content_type', $m->enumValues());

        $m = new PagesModel();
        $frm->label('Status')->select('status', $m->enumValues());
        $this->buildSelects($frm);
        echo $frm->close();
        return view('pages/' . $this->viewData['view_file'], $this->viewData);
    }
}
