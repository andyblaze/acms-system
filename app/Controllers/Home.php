<?php

namespace App\Controllers;
use App\Models\PagesModel;
use App\Models\HtmlIdentifiersModel;
use App\Models\PageTemplatesModel;
use App\Models\RendererClassesModel;
use App\Models\ContentMapModel;
use App\Models\TextContentModel;
use App\Models\MediaContentModel;
use App\Libraries\FormBuilder;
use App\Libraries\AttributesManager;


class Home extends BaseController {
    protected function buildSelects($frm) {        
        $fields = [
            'page_id'               => PagesModel::class,
            'html_identifier_id'    => HtmlIdentifiersModel::class,
            'template_id'           => PageTemplatesModel::class,
            'renderer_id'           => RendererClassesModel::class
        ];
        foreach ( $fields as $field => $modelClass ) {
            $m = new $modelClass();
            $frm->label(humanize($field))->select($field, $m->asIdValueMap());
        }
    }
    protected function buildEnums($frm) {
        $fields = [
            'content_type'  => ContentMapModel::class,
            'status'        => PagesModel::class,
            'format'        => TextContentModel::class,
            'type'          => MediaContentModel::class
        ];
        foreach ( $fields as $field => $modelClass ) {
            $m = new $modelClass();
            $frm->label(humanize($field))->select($field, $m->enumValues());
        }
     }
    public function index(): string {
        helper('inflector');
        $frm = new FormBuilder(new AttributesManager());
        $frm->open();
        $this->buildEnums($frm);
        $this->buildSelects($frm);
        $frm->label('Text content')->input('text_content');
        $frm->label('Html content')->textarea('html_content');
        echo $frm->close();
        return view('pages/' . $this->viewData['view_file'], $this->viewData);
    }
}
