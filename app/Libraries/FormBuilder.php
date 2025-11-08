<?php
namespace App\Libraries;

use App\Libraries\AttributesManager;
use Config\FormTheme;

class Control {
    protected array $cfg = [];
    protected array $theme = [];
    protected ?object $attributes = null;
    protected string $tag = '';
    private array $nonVoids = [
        'textarea', 'button'
    ];
    public function __construct($theme=null) {
        $cfg = new FormTheme();
        $this->theme = $cfg->{$theme} ?? [];
        $this->attributes = new AttributesManager();    
    }
    protected function addThemeClass(string $key) {
        if ( array_key_exists($key, $this->theme) ) {
            $cls = $this->theme[$key]['class'];
            $this->attributes->addClass($cls);            
        }
    }   
    public function getAttr($attr) {
        return $this->attributes->get($attr);
    }
    public function setAttr($k, $v) {
        $this->attributes->set($k, $v);
    }
    public function init(array $cfg, string $tag, string $themeKey, string $attrs) {
        $this->cfg = $cfg;
        $this->tag = $tag;
        $this->attributes->initAttributes($attrs);
        $this->attributes->merge($cfg);
        $this->addThemeClass($themeKey);
    }
    public function render() {
        if ( in_array($this->tag, $this->nonVoids) ) { 
            $value = $this->attributes->get('value');
            $this->attributes->remove('value');
            $this->attributes->remove('type');
            $tag =  "<{$this->tag} " . 
                    $this->attributes->toString() . 
                    ">{$value}</{$this->tag}>\n";
        }
        else {
            $tag =  "<{$this->tag} " . 
                    $this->attributes->toString() .
                    " />\n";
        }
        return $tag;
    }
}

class LabelControl extends Control {
    public function render() {
        $value = $this->attributes->get('value');
        $this->attributes->remove('value');
        return  "<{$this->tag} " . 
                $this->attributes->toString() .
                ">{$value}</{$this->tag}>\n";
    }
}

class SelectControl extends Control {
    protected function renderOptions($opts, $selected) {
        $result = '';
        foreach ( $opts as $val=>$txt ) {
            $sel = in_array($val, $selected) ? ' selected="selected"' : '';
            $result .= "<option value=\"{$val}\"{$sel}>{$txt}</option>\n";
        }
        return $result;
    }
    public function render() {
        $opts = $this->attributes->get('options');
        $sel = $this->attributes->get('selected');
        $options = $this->renderOptions($opts, $sel);
        $this->attributes->remove('options');
        $this->attributes->remove('selected');
        return  "<{$this->tag} " . 
                $this->attributes->toString() .
                ">{$options}</{$this->tag}>\n";
    }
}
class FormControl extends Control {
    public function open() {
        return  "<{$this->tag} " .
                $this->attributes->toString() .
                ">";
    }
    public function close() {
        return "</{$this->tag}>\n";
    }
}
class FieldsetControl extends Control {
    public function open(string $legend_text) {
        $legend = ($legend_text === '' ? '' : "<legend>{$legend_text}</legend>");
        return  "<{$this->tag} " .
                $this->attributes->toString() .
                ">{$legend}\n";
    }
    public function close() {
        return "</{$this->tag}>\n";
    }
}
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
    protected $pendingLabel = null;
    protected $pendingControl = null;
    protected string $pairDirection = ''; // 'label-first' or 'control-first'

    protected array $theme = [];
    
    public function __construct(string $theme='bootstrap') {
        helper('form');
        $cfg = new FormTheme();
        $this->themeName = $theme;
        $this->theme = $cfg->$theme ?? [];
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
    protected function pair(Control $first, Control $second, string $direction): void {
        // Resolve IDs/for attributes
        $id = $first->getAttr('for') ?: $second->getAttr('id') ?: randomStr();
        $first->setAttr('for', $id);
        $second->setAttr('id', $id);

        // Determine order
        if ( $direction === 'label-first' ) {
            $this->htm .= $first->render() . $second->render();
        } else {
            $this->htm .= $first->render() . $second->render();
        }
    }
    protected function checkPending() {
        if ( $this->pendingLabel ) {
            // Label came first -> label-first
            $this->pair($this->pendingLabel, $this->control, 'label-first');
            $this->pendingLabel = null;
        } else {
            $this->pendingControl = $this->control;
            $this->pairDirection = 'control-first';
        }
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
    protected function addInput($name, $value, $extra, string $type, string $themeKey='input', bool $typeToTag=false) {
        $cfg = $this->stdConfig($name, $value, $type);
        $tag = (true === $typeToTag ? $type : 'input');
        $this->control->init($cfg, $tag, $themeKey, $extra); 

        $this->checkPending();
        
        //$this->htm .= $this->control->render();
        return $this;
    }
    public function hidden(string $name, $value='', string $extra=''): static {
        return $this->addInput($name, $value, $extra, 'hidden');
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
        $this->fields[] = $name;
        $ctrl = new SelectControl($this->themeName);
        $cfg = [
            'name'=>$name,
            'options'=>$options, 
            'selected'=>$selected
        ];
        if ( true === $multi ) 
            $cfg['multiple'] = 'multiple';
        $ctrl->init($cfg, 'select', 'select', $extra);
        $this->htm .= $ctrl->render();
        return $this;
    }
    public function select(string $name, array $options=[], array $selected=[], string $extra=''): static {
        return $this->addSelect($name, $options, $selected, $extra, false);
    }
    public function multiselect(string $name='', array $options=[], array $selected=[], $extra=''): static {
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
        $cfg = $this->stdConfig($name, $value, $type);
        $cfg['checked'] = $checked;
        return $this->addInput($name, $value, $extra, $type, $type);
    }
    private function inputGroup(string $type, $name, $options, $checked, $extra): static {
        $workingName = $name;
        if ( $type === 'checkbox' && strpos($name, '[]') === false )
            $workingName .= '[]';
            
        if ( $extra !== '' )
            $extra = ' ' . $extra;
        $this->wrap('div');
        foreach ( $options as $opt => $text ) {
            $boxId = $name . $opt;
            $isChecked = in_array($opt, $checked, true);
            $this->wrap('div', 'class="form-check"')->
                {$type}($workingName, $opt, $isChecked, "id=\"{$boxId}\"")
                 ->label($text, $boxId)->
            unwrap();
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
        if ($this->pendingControl) {
            // Control came first -> control-first
            $this->pair($this->pendingControl, $label, 'control-first');
            $this->pendingControl = null;
        } else {
            $this->pendingLabel = $label;
            $this->pairDirection = 'label-first';
        }
        //$this->htm .= $ctrl->render();
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
        $attrs = $extra === '' ? '' : ' ' . $extra; //$this->attrToString($extra, null, false);
        $this->htm .= "<{$tag}{$attrs}>";
        $this->wrapTag = $tag;
        return $this;
    }
    public function html(string $htm): static {
        $this->htm .= $htm;
        return $this;
    }
    public function close($extra=''): string {
        $this->fieldset_close();
        $this->hidden('field_names', implode(',', $this->fields));
        $this->hidden('nonce', randomStr());
        $this->htm .= $this->formCtrl->close();
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
