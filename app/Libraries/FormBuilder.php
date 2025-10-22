<?php
namespace App\Libraries;

class FormBuilder {
    protected $htm = '';
    protected $fieldset_open = false;
    
    public function __construct() {
        helper('form');
    }
    
    protected open_form($action='', $attributes='', $hidden=[], $multi) {
        if ( $multi === true ) 
            $this->htm .= form_open_multipart($action, $attributes, $hidden);
        else 
            $this->htm .= form_open($action, $attributes, $hidden);
        return $this;
    }
    public function open($action='', $attributes='', $hidden=[]) {
        return $this->open_form($action, $attributes, $hidden, false);
    }
    public function open_multipart($action='', $attributes='', $hidden=[]) {
        return $this->open_form($action, $attributes, $hidden, true);
    }
    public function hidden($name, $value='', array $extra=[]) {
        if ( empty($extra) )
            $this->htm .= form_hidden($name, $value);
        else {
            $extra += ['name'=>$name, 'value'=>$value, 'type'=>'hidden'];
            $this->form_input($extra);
        }
        return $this;    
    }
    public function input($data='', $value='', $extra='', $type='text') {
        $this->htm .= form_input($data, $value, $extra, $type);
        return $this;
    }
    public function password($data='', $value='', $extra='') {
        $this->htm .= form_password($data, $value, $extra);
        return $this;
    }
    public function upload($data='', $value='', $extra='') {
        $this->htm .= form_upload($data, $value, $extra);
        return $this;
    }
    public function textarea($data='', $value='', $extra='') {
        $this->htm .= form_textarea($data, $value, $extra);
        return $this;
    }
    public function select($name='', $options=[], $selected=[], $extra='') {
        $this->htm .= form_dropdown($name, $options, $selected, $extra);
        return $this;
    }
    public function multiselect($name='', $options=[], $selected=[], $extra='') {
        $this->htm .= form_multiselect($name, $options, $selected, $extra);
        return $this;
    }
    public function fieldset($legend_text='', $attributes=[]) {
        $this->fieldset_close();
        $this->htm .= form_fieldset($legend_text, $attributes);
        $this->fieldset_open = true;
        return $this;
    }
    public function fieldset_close($extra='') {
        if ( $this->fieldset_open === true ) {
            $this->htm .= form_fieldset_close($extra);
            $this->fieldset_open = false;
        }
        return $this;
    }
    public function checkbox($data='', $value='', $checked=false, $extra='') {
        $this->htm .= form_checkbox($data, $value, $checked, $extra);
        return $this;
    }
    public function radio($data='', $value='', $checked=false, $extra='') {
        $this->htm .= form_radio($data, $value, $checked, $extra);
        return $this;
    }
    public function label($label_text='', $id='', $attributes=[]) {
        $this->htm .= form_label($label_text, $id, $attributes);
        return $this;
    }
    public function submit($data='', $value='', $extra='') {
        $this->htm .= form_submit($data, $value, $extra);
        return $this;
    }
    public function reset($data='', $value='', $extra='') {
        $this->htm .= form_reset($data, $value, $extra);
        return $this;
    }
    public function button($data='', $content='', $extra='') {
        $this->htm .= form_button($data, $content, $extra);
        return $this;
    }
    public function close($extra='') {
        $this->fieldset_close();
        $this->htm .= form_close($extra);
        return $this;
    }
    public function html() {
        return $this->htm;
    }
    public function clear() {
        $this->htm = '';
    }
}
