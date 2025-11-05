<?php
namespace App\Libraries;

use App\Libraries\AttributesManager;
use Config\FormTheme;

class Control {
    private array $cfg = [];
    private array $theme = [];
    private ?object $attributes = null;
    private string $tag = '';
    private array $nonVoids = [
        'textarea', 'button'
    ];
    public function __construct() {
        $cfg = new FormTheme();
        $this->theme = $cfg->bootstrap ?? [];
        $this->attributes = new AttributesManager();    
    }
    protected function addThemeClass(string $key) {
        if ( array_key_exists($key, $this->theme) ) {
            $cls = $this->theme[$key]['class'];
            $this->attributes->addClass($cls);            
        }
    }    
    public function init(array $cfg, string $tag, string $themeKey, string $attrs) {
        $this->cfg = $cfg;
        $this->tag = $tag;
        $this->attributes->initAttributes($attrs);
        $this->attributes->merge($cfg);
        $this->addThemeClass($themeKey);
    }
    public function render() {
        if ( in_array($this->tag, $this->nonVoids) ) { pre(99);
            $value = $this->attributes->get('value');
            $this->attributes->remove('value');
            $this->attributes->remove('type');
            $openTag = "<{$this->tag} " . $this->attributes->toString() . '>';
            $closeTag = "{$value}</{$this->tag}>";
        }
        else {
            $openTag = "<{$this->tag} " . $this->attributes->toString();
            $closeTag = ' />';
        }
        return $openTag . $closeTag;
    }
}

class FormBuilder {
    protected $fields = [];
    protected $pendingLabel = null;
    protected $htm = '';
    protected $fieldset_open = false;
    protected $form_opened = false;
    protected $attributes = null;
    protected $control = null;

    protected array $theme = [];
    
    public function __construct(string $theme='bootstrap') {
        helper('form');
        $cfg = new FormTheme();
        $this->theme = $cfg->$theme ?? [];
        $this->attributes = new AttributesManager();
        $this->control = new Control();
    }    
    protected function addAttribute($atts, $type=null, $addId=true) {
        if ( array_key_exists($type, $this->theme) ) {
            $cls = $this->theme[$type]['class'];
            $this->attributes->initAttributes($atts, $addId)->addClass($cls);            
        }
    }    
    protected function setPendingLabel($text, $id, $atts) {
        $this->pendingLabel = ['text'=>$text, 'for'=>$id, 'attributes'=>$atts];
    }
    protected function setIds() {
        if ( $this->pendingLabel === null ) return;
        $id = $this->attributes->get('id'); 
        if ( $id !== null ) {
            $this->pendingLabel['for'] = $id;
        }
    }
    protected function attrToString($atts, $type=null, $addId=true) {
        $this->addAttribute($atts, $type, $addId);
        return $this->attributes->toString();
    }  
    protected function attrToArray($atts, $type=null, $addId=true) {
        $this->addAttribute($atts, $type, $addId);
        return $this->attributes->toArray();    
    }
    protected function open_form($action='', $attributes='', $hidden=[], $multi=false): static {
        $this->clear();
        if ( $multi === true ) 
            $this->htm .= form_open_multipart($action, $this->attrToString($attributes, null, false), $hidden);
        else 
            $this->htm .= form_open($action, $this->attrToString($attributes, null, false), $hidden);
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
            $attrs = $this->attrToArray($extra);
            $attrs += ['name'=>$name, 'value'=>$value, 'type'=>'hidden'];
            $this->form_input($extra);
        }
        return $this;    
    }
    private function addField(string $helper, $name, $value='', $extra='', ?string $type = null): static {
        $this->fields[] = $name;
        if ( $type !== null ) {
            $this->htm .= $helper($name, $value, $extra, $type);
        } else {
            $this->htm .= $helper($name, $value, $extra);
        }
        return $this;
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
        $this->htm .= $this->control->render();
        return $this;
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
    protected function addSelect(string $name='', array $options=[], array $selected=[], $extra='', $multi): static {
        $this->fields[] = $name;
        if ( true === $multi )
            $this->htm .= form_multiselect($name, $options, $selected, $this->attrToString($extra, 'select'));
        else
            $this->htm .= form_dropdown($name, $options, $selected, $this->attrToString($extra, 'select'));
        return $this;
    }
    public function select(string $name, array $options=[], array $selected=[], string $extra=''): static {
        return $this->addSelect($name, $options, $selected, $extra, false);
    }
    public function multiselect(string $name='', array $options=[], array $selected=[], $extra=''): static {
        return $this->addSelect($name, $options, $selected, $extra, true);
    }
    public function fieldset($legend_text='', $attributes=[]): static {
        $this->fieldset_close();
        $this->htm .= form_fieldset($legend_text, $this->attrToArray($attributes, null, false));
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
    protected function tickable($name, $value, $checked, $extra, $type): static {
        $cfg = $this->stdConfig($name, $value, $type);
        $cfg['checked'] = $checked;
        return $this->addInput($name, $value, $extra, $type, $type);
    }
    public function checkbox(string $name, $value='', bool $checked=false, string $extra=''): static {
        return $this->tickable($name, $value, $checked, $extra, 'checkbox');
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
    public function radio(string $name, $value='', bool $checked=false, string $extra=''): static {
        return $this->tickable($name, $value, $checked, $extra, 'radio');
    }
    public function label(string $label_text, string $id='', string $extra=''): static {
        $this->htm .= form_label($label_text, $id, $this->attrToArray($attributes, 'label', false));
        return $this;
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
    public function html(string $htm): static {
        $this->htm .= $htm;
        return $this;
    }
    public function close($extra=''): string {
        $this->fieldset_close();
        $this->hidden('field_names', implode(',', $this->fields));
        $this->hidden('nonce', randomStr());
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
}
