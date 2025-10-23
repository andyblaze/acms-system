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
            $this->htm .= form_open_multipart($action, $this->attrToString($attributes), $hidden);
        else 
            $this->htm .= form_open($action, $this->attrToString($attributes), $hidden);
        $this->form_opened = true;
        return $this;
    }
    public function open($action='', $attributes='', $hidden=[]): static {
        return $this->open_form($action, $attributes, $hidden, false);
    }
    public function open_multipart($action='', $attributes='', $hidden=[]): static {
        return $this->open_form($action, $attributes, $hidden, true);
    }
    public function hidden($name, $value='', $extra=''): static {
        if ( $extra === '' )
            $this->htm .= form_hidden($name, $value);
        else {
            $attrs = $this->attrToString($extra);
            $attrs += ['name'=>$name, 'value'=>$value, 'type'=>'hidden'];
            $this->form_input($extra);
        }
        return $this;    
    }
    private function addField(string $helper, $data = '', $value = '', $extra = '', ?string $type = null): static {
        $extra = $this->attrToString($extra);
        if ( $type !== null ) {
            $this->htm .= $helper($data, $value, $extra, $type);
        } else {
            $this->htm .= $helper($data, $value, $extra);
        }
        return $this;
    }
    public function input($data='', $value='', $extra='', $type='text'): static {
        return $this->addField('form_input', $data, $value, $extra, $type);
    }
    public function password($data='', $value='', $extra=''): static {
        return $this->addField('form_password', $data, $value, $extra);
    }
    public function upload($data='', $value='', $extra=''): static {
        return $this->addField('form_upload', $data, $value, $extra);
    }
    public function color($data='', $value='', $extra=''): static {
        return $this->input($data, $value, $extra, 'color');
    }
    public function number($data='', $value='', $extra=''): static {
        return $this->input($data, $value, $extra, 'number');
    }
    public function date($data='', $value='', $extra=''): static {
        return $this->input($data, $value, $extra, 'date');
    }
    public function range($data='', $value='', $extra=''): static {
        return $this->input($data, $value, $extra, 'range');
    }
    public function textarea($data='', $value='', $extra=''): static {
        return $this->addField('form_textarea', $data, $value, $extra);
    }
    public function select(string $name='', array $options=[], array $selected=[], $extra=''): static {
        $this->htm .= form_dropdown($name, $options, $selected, $this->attrToString($extra));
        return $this;
    }
    public function multiselect(string $name='', array $options=[], array $selected=[], $extra=''): static {
        $this->htm .= form_multiselect($name, $options, $selected, $this->attrToString($extra));
        return $this;
    }
    public function fieldset($legend_text='', $attributes=[]): static {
        $this->fieldset_close();
        $this->htm .= form_fieldset($legend_text, $this->attrToString($attributes));
        $this->fieldset_open = true;
        return $this;
    }
    public function fieldset_close($extra=''): static {
        if ( $this->fieldset_open === true ) {
            $this->htm .= form_fieldset_close($this->attrToString($extra));
            $this->fieldset_open = false;
        }
        return $this;
    }
    public function checkbox($data='', $value='', $checked=false, $extra=''): static {
        $this->htm .= form_checkbox($data, $value, $checked, $this->attrToString($extra));
        return $this;
    }
    private function inputGroup(string $type, string $name, array $options, array $checked=[], string $extra=''): static {
        if ( $extra !== '' )
            $extra = ' ' . $extra;
        foreach ($options as $opt => $text) {
            $boxId = $name . $opt;
            $isChecked = in_array($opt, $checked, true);
            $this->label($text, $boxId)
                 ->{$type}($name, $opt, $isChecked, "id=\"{$boxId}\"{$extra}");
        }
        return $this;
    }
    public function checkboxGroup($name, $options, $checked=[], $extra=''): static {
        return $this->inputGroup('checkbox', $name, $options, $checked, $extra);
    }
    public function radioGroup($name, $options, $checked=[], $extra=''): static {
        return $this->inputGroup('radio', $name, $options, $checked, $extra);
    }
    public function radio($data='', $value='', $checked=false, $extra=''): static {
        $this->htm .= form_radio($data, $value, $checked, $this->attrToString($extra));
        return $this;
    }
    public function label($label_text='', $id='', $attributes=[]): static {
        $this->htm .= form_label($label_text, $id, $this->attrToString($attributes));
        return $this;
    }
    public function submit($data='', $value='', $extra=''): static {
        $this->htm .= form_submit($data, $value, $this->attrToString($extra));
        return $this;
    }
    public function reset($data='', $value='', $extra=''): static {
        $this->htm .= form_reset($data, $value, $this->attrToString($extra));
        return $this;
    }
    public function button($data='', $content='', $extra=''): static {
        $this->htm .= form_button($data, $content, $this->attrToString($extra));
        return $this;
    }
    public function html(string $htm): static {
        $this->htm .= $htm;
        return $this;
    }
    public function close($extra=''): string {
        $this->fieldset_close();
        $this->htm .= form_close($this->attrToString($extra));
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
}
