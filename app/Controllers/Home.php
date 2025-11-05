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
        $frm = new FormBuilder();
        $frm->open('/api/content/save')->fieldset();
        //$this->buildEnums($frm);
        //$frm->fieldset();
        //$this->buildSelects($frm);
        $frm->fieldset();
        $frm->label('Text content')->input('text_content');
        $frm->label('Password')->password('password');
        $frm->label('Email')->email('email');
        $frm->label('File')->upload('file');
        $frm->label('Color')->color('color');
        $frm->label('Number')->number('number', 2, 'min="0" max="6"');
        $frm->label('Date')->date('date');
        $frm->label('Range')->range('range', 3, 'min="0" max="6"');
        //$frm->label('Html content')->textarea('html_content');
        $frm->submit('', 'Send');
        echo $frm->close();
        return view('pages/' . $this->viewData['view_file'], $this->viewData);
    }
}
