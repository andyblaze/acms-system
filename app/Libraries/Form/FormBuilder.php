<?php
namespace App\Libraries\Form;

use App\Libraries\Form\Control;
use App\Libraries\Form\AttributesManager;
use App\Libraries\Form\ControlBinder;
use Config\FormTheme;
use App\Libraries\Form\FormSecurity;

class FormBuilder {
    protected $fields = [];
    protected $htm = '';
    protected $fieldset_open = false;
    protected $form_opened = false;
    protected $wrapTag = '';
    protected $themeName = '';
    protected $attributes = null;
    protected $control = null;
    protected $formCtrl = null;
    protected $fieldsetCtrl = null;
    protected array $theme = [];
    
    public function __construct(string $theme='bootstrap') {
        helper('form');
        $cfg = new FormTheme();
        $this->themeName = $theme;
        $this->theme = $cfg->$theme ?? [];
        $this->ctrlBinder = new ControlBinder($this->theme);
        $this->attributes = new AttributesManager();
        $this->control = new Control($theme);
    }    
    protected function addAttribute($atts, $type=null, $addId=true) {
        if ( array_key_exists($type, $this->theme) ) {
            $cls = $this->theme[$type]['class'];
            $this->attributes->initAttributes($atts, $addId)->addClass($cls);            
        }
    }    
    protected function attrToString($atts) { //, $type=null, $addId=true) {
        $this->attributes->initAttributes($atts);//, $type, $addId);
        return $this->attributes->toString();
    }  
    protected function attrToArray($atts, $type=null, $addId=true) {
        $this->addAttribute($atts, $type, $addId);
        return $this->attributes->toArray();    
    }
    public function open(string $action='', string $extra=''): static {
        $this->formCtrl = new FormControl();
        $cfg = ['action'=>base_url($action), 'method'=>'post', 'enctype'=>'multipart/form-data'];
        $this->formCtrl->init($cfg, 'form', 'form', $extra);
        $this->htm .= $this->formCtrl->open();
        $this->form_opened = true;
        return $this;
    }
    public function open_multipart(string $action='', string $extra=''): static {
        return $this->open($action, $extra);
    }
    protected function stdConfig($name, $value, $type) {
        return [
            'name'=>$name,
            'value'=>$value,
            'type'=>$type
        ];
    }
    protected function addFieldName($name) {
        if ( $name === '' ) return; // handles un-named buttons for instance
        $n = str_replace('[]', '', $name);
        $this->fields[$n] = $n;
    }
    protected function addInput($name, $value, $extra, string $type, string $themeKey='input', bool $typeToTag=false) { 
        $this->addFieldName($name);
        $cfg = $this->stdConfig($name, $value, $type);
        $tag = (true === $typeToTag ? $type : 'input');
        $this->control->init($cfg, $tag, $themeKey, $extra); 
        if ( $this->ctrlBinder->isUnpaired($type) )
            $this->htm .= $this->control->render();
        else
            $this->htm .= $this->ctrlBinder->handleControl($this->control); 
        return $this;
    }
    public function hidden(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'hidden', 'hidden');
    }
    public function input(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'text');
    }
    public function password(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'password');
    }
    public function email(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'email');
    }
    public function upload(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'file');
    }
    public function color(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'color', 'color');
    }
    public function number(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'number', 'number');
    }
    public function date(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'date', 'date');
    }
    public function range(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'range', 'range');
    }
    public function textarea($name, $value='', $extra=''): static {
        return $this->addInput($name, $value, $extra, 'textarea', 'textarea', true);
    }
    public function checkbox(string $name, $value='', bool $checked=false, string $extra=''): static {
        return $this->tickable($name, $value, $checked, $extra, 'checkbox');
    }
    public function radio(string $name, $value='', bool $checked=false, string $extra=''): static {
        return $this->tickable($name, $value, $checked, $extra, 'radio');
    }
    public function submit(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'submit', 'submit');
    }
    public function reset(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'reset', 'reset');
    }
    public function button(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'button', 'button', true);
    }
    protected function addSelect($name, $options, $selected, $extra, $multi): static {
        $this->addFieldName($name);
        $ctrl = new SelectControl($this->themeName);
        $cfg = [
            'name'=>$name,
            'options'=>$options, 
            'selected'=>$selected
        ];
        if ( true === $multi ) 
            $cfg['multiple'] = 'multiple';
        $ctrl->init($cfg, 'select', 'select', $extra);
        $this->htm .= $this->ctrlBinder->handleControl($ctrl);
        return $this;
    }
    public function select(string $name, array $options=[], array $selected=[], string $extra=''): static {
        return $this->addSelect($name, $options, $selected, $extra, false);
    }
    public function multiselect(string $name='', array $options=[], array $selected=[], $extra=''): static {
        $name = (substr($name, -2) === '[]') ? $name : $name . '[]';
        return $this->addSelect($name, $options, $selected, $extra, true);
    }
    public function fieldset($legend_text='', $extra=''): static {
        $this->fieldset_close();
        if ( $this->fieldsetCtrl === null )
            $this->fieldsetCtrl = new FieldsetControl($this->themeName);
        $this->fieldsetCtrl->init([], 'fieldset', 'fieldset', $extra);
        $this->htm .= $this->fieldsetCtrl->open($legend_text);
        $this->fieldset_open = true;
        return $this;
    }
    public function fieldset_close(): static {
        if ( $this->fieldsetCtrl !== null ) {
            $this->htm .= $this->fieldsetCtrl->close();
            $this->fieldset_open = false;
        }
        return $this;
    }
    protected function tickable($name, $value, $checked, $extra, $type): static {
        $this->addFieldName($name);
        $cfg = $this->stdConfig($name, $value, $type);
        $cfg['checked'] = $checked;
        $this->addInput($name, $value, $extra, $type, $type);
        return $this;
    }
    private function inputGroup(string $type, $name, $options, $checked, $extra): static {
        $baseName = trim($name, '[]');
        //$workingName = $name;
        if ( $type === 'checkbox' )
            $name .= '[]';
            
        if ( $extra !== '' )
            $extra = ' ' . $extra;
        
        $this->wrap('div');
        foreach ( $options as $opt => $text ) {
            $boxId = $baseName . $opt;
            $isChecked = in_array($opt, $checked, true);
            $this->{$type}($name, $opt, $isChecked, "id=\"{$boxId}\"")
                 ->label($text, $boxId);
        }
        $this->unwrap();
        return $this;
    }
    public function checkboxGroup(string $name, array $options, array $checked=[], string $extra=''): static {
        return $this->inputGroup('checkbox', $name, $options, $checked, $extra);
    }
    public function radioGroup(string $name, array $options, array $checked=[], string $extra=''): static {
        return $this->inputGroup('radio', $name, $options, $checked, $extra);
    }
    public function label(string $label_text, string $id='', string $extra=''): static {
        $label = new LabelControl($this->themeName);
        $cfg = ['value'=>$label_text];
        if ( $id !== '' ) 
            $cfg['for'] = $id;
        $label->init($cfg, 'label', 'label', $extra);
        $this->htm .= $this->ctrlBinder->handleLabel($label);
        return $this;
    }
    public function unwrap() {
        if ( $this->wrapTag !== '' ) {
            $this->htm .= "</{$this->wrapTag}>\n";
        }
        $this->wrapTag = '';
        return $this;
    }
    public function wrap(string $tag, string $extra='') {
        $this->unwrap();
        $attrs = $this->attrToString($extra); 
        $this->htm .= "<{$tag}{$attrs}>";
        $this->wrapTag = $tag;
        return $this;
    }
    public function html(string $htm): static {
        $this->htm .= $htm;
        return $this;
    }
    public function close(): static {
        $this->unwrap()->fieldset_close();
        $sec = new FormSecurity();
        $data = $sec->secure($this->fields);

        $this->hidden('field_names', $data['fields'])
             ->hidden('nonce', $data['nonce'])
             ->hidden('checksum', $data['checksum']);
             
        $this->htm .= $this->formCtrl->close();     
        return $this;
    }
    public function clear(): void {
        $this->htm = '';
        $this->fieldset_open = false;
        $this->form_opened = false;
        $this->fields = [];
    }
    public function render(): string {
        $output = $this->htm;
        $this->clear();
        return $output;
    }
}
