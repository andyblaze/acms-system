<?php 
namespace App\Libraries\Form;

use App\Libraries\Form\AttributesManager;
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
    public function getTag() {
        return $this->tag;
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
            $tag =  "<{$this->tag}" . 
                    $this->attributes->toString() . 
                    ">{$value}</{$this->tag}>\n";
        }
        else {
            $tag =  "<{$this->tag}" . 
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
        return  "<{$this->tag}" . 
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
        return  "<{$this->tag}" . 
                $this->attributes->toString() .
                ">{$options}</{$this->tag}>\n";
    }
}
class FormControl extends Control {
    public function open() {
        return  "<{$this->tag}" .
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
        return  "<{$this->tag}" .
                $this->attributes->toString() .
                ">{$legend}\n";
    }
    public function close() {
        return "</{$this->tag}>\n";
    }
}