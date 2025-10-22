<?php
namespace App\Libraries;

class FormBuilder {
    protected $htm = '';
    protected $fieldset_open = false;
    protected $form_opened = false;
    
    public function __construct() {
        helper('form');
    } 
    protected function attrToString($extra): string {
        if ( is_array($extra) ) {
            return implode(' ', array_map(fn($k, $v)=>$k . '="' . esc($v) . '"', array_keys($extra), $extra));
        }
        return $extra;
    }    
    protected function open_form($action='', $attributes='', $hidden=[], $multi=false): static {
        $this->clear();
        if ( $multi === true ) 
            $this->htm .= form_open_multipart($action, $attributes, $hidden);
        else 
            $this->htm .= form_open($action, $attributes, $hidden);
        $this->form_opened = true;
        return $this;
    }
    public function open($action='', $attributes='', $hidden=[]): static {
        return $this->open_form($action, $attributes, $hidden, false);
    }
    public function open_multipart($action='', $attributes='', $hidden=[]): static {
        return $this->open_form($action, $attributes, $hidden, true);
    }
    public function hidden($name, $value='', array $extra=[]): static {
        if ( empty($extra) )
            $this->htm .= form_hidden($name, $value);
        else {
            $extra += ['name'=>$name, 'value'=>$value, 'type'=>'hidden'];
            $this->form_input($extra);
        }
        return $this;    
    }
    public function input($data='', $value='', $extra='', $type='text'): static {
        $this->htm .= form_input($data, $value, $extra, $type);
        return $this;
    }
    public function password($data='', $value='', $extra=''): static {
        $this->htm .= form_password($data, $value, $extra);
        return $this;
    }
    public function upload($data='', $value='', $extra=''): static {
        $this->htm .= form_upload($data, $value, $extra);
        return $this;
    }
    public function textarea($data='', $value='', $extra=''): static {
        $this->htm .= form_textarea($data, $value, $extra);
        return $this;
    }
    public function select($name='', $options=[], $selected=[], $extra=''): static {
        $this->htm .= form_dropdown($name, $options, $selected, $extra);
        return $this;
    }
    public function multiselect($name='', $options=[], $selected=[], $extra=''): static {
        $this->htm .= form_multiselect($name, $options, $selected, $extra);
        return $this;
    }
    public function fieldset($legend_text='', $attributes=[]): static {
        $this->fieldset_close();
        $this->htm .= form_fieldset($legend_text, $attributes);
        $this->fieldset_open = true;
        return $this;
    }
    public function fieldset_close($extra=''): static {
        if ( $this->fieldset_open === true ) {
            $this->htm .= form_fieldset_close($extra);
            $this->fieldset_open = false;
        }
        return $this;
    }
    public function checkbox($data='', $value='', $checked=false, $extra=''): static {
        $this->htm .= form_checkbox($data, $value, $checked, $extra);
        return $this;
    }
    public function checkboxes($name, $options, $checked=[], $extra=''): static {
        foreach ( $options as $opt=>$text ) {
            $boxId = $name . $opt;
            $boxChecked = in_array($opt, $checked);
            $this->label($text, $boxId)->checkbox($name, $opt, $boxChecked, "id=\"{$boxId}\"");
        }
        return $this;
    }
    public function radios($name, $options, $checked=[], $extra=''): static {
        foreach ( $options as $opt=>$text ) {
            $boxId = $name . $opt;
            $boxChecked = in_array($opt, $checked);
            $this->label($text, $boxId)->radio($name, $opt, $boxChecked, "id=\"{$boxId}\"");
        }
        return $this;
    }
    public function radio($data='', $value='', $checked=false, $extra=''): static {
        $this->htm .= form_radio($data, $value, $checked, $extra);
        return $this;
    }
    public function label($label_text='', $id='', $attributes=[]): static {
        $this->htm .= form_label($label_text, $id, $attributes);
        return $this;
    }
    public function submit($data='', $value='', $extra=''): static {
        $this->htm .= form_submit($data, $value, $extra);
        return $this;
    }
    public function reset($data='', $value='', $extra=''): static {
        $this->htm .= form_reset($data, $value, $extra);
        return $this;
    }
    public function button($data='', $content='', $extra=''): static {
        $this->htm .= form_button($data, $content, $extra);
        return $this;
    }
    public function close($extra=''): string {
        $this->fieldset_close();
        $this->htm .= form_close($extra);
        $output = $this->htm;        
        $this->clear();
        return $output;
    }
    public function clear(): void {
        $this->htm = '';
        $this->fieldset_open = false;
        $this->form_opened = false;
    }
    public function render(): string {
        return $this->htm;
    }
    public function html(): string {
        return $this->render();
    }
}
