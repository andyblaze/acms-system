<?php

namespace App\Controllers;
use App\Models\PagesModel;
use App\Models\HtmlIdentifiersModel;
use App\Models\PageTemplatesModel;
use App\Models\RendererClassesModel;
use App\Models\ContentMapModel;
use App\Models\TextContentModel;
use App\Models\MediaContentModel;
use App\Libraries\Form\FormBuilder;

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
        $frm = new FormBuilder('bootstrap');
        $frm->open('/api/content/save')->fieldset()->
        /*wrap('div', 'id="twirl" class="boob"')->label('foo')->input('foo', 'bar')->unwrap()->
        checkbox('abc', 12)->label('Abc')->
        radio('xyz', 12)->label('Xyz')->
        label('Select content')->select('abc', ['a', 'b', 'c'], [], '')->
        label('mSelect content')->multiselect('mabc', ['a', 'b', 'c'], [], '')->
        label('Text content')->input('text_content')->
        label('Password')->password('password')->
        label('Email')->email('email')->
        label('File')->upload('file')->
        checkboxGroup('ghg', ['h', 'k', 'l'])->
        radioGroup('vbv', ['w', 'e', 'r'])->
        // wrap will unwrap() before it wraps again
        wrap('div', 'class="abc"')->label('Color')->color('color')->
        wrap('div')->label('Number')->number('number', 2, 'min="0" max="6"')->
        wrap('div')->label('Date')->date('date')->
        // manual unwrap
        wrap('div')->label('Range')->range('range', 3, 'min="0" max="6"')->unwrap()->
        label('Html content')->textarea('html_content', 'poop')->*/
        wrap('div')->submit('', 'Send')->unwrap()->
        wrap('div')->reset('', 'Reset')->unwrap()->
        wrap('div')->button('', 'Btn')->unwrap()->
        close();
        echo $frm->render();
        return view('pages/' . $this->viewData['view_file'], $this->viewData);
    }
}
