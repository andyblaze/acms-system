<?php
namespace App\Libraries\Form;

class ControlBinder {
    protected $pendingLabel = null;
    protected $pendingControl = null;
    protected string $pairDirection = ''; // 'label-first' or 'control-first'  
    protected $theme = [];
    protected $unpaired = ['hidden', 'submit', 'reset', 'button'];
    public function __construct($theme) {
        $this->theme = $theme;
    }
    public function isUnpaired($type) {
        return in_array($type, $this->unpaired);
    }
    protected function isInline($ctrl, $cssClass) {
        $classes = explode(' ', $ctrl->getAttr('class'));
        $idx = array_search($cssClass, $classes);
        if ( false === $idx )  
            return false;
        else {
            unset($classes[$idx]);
            $ctrl->setAttr('class', implode(' ', $classes));
            return true;
        }
    }
    protected function pair(Control $label, Control $ctrl, string $direction): string {
        // If only one exists, render it and bail.
        if ( ! $label && $ctrl ) 
            return $ctrl->render();
        
        if ( $label && ! $ctrl )
            return $label->render();

        // Resolve IDs/for attributes
        $id = $label->getAttr('for') ?: $ctrl->getAttr('id') ?: randomStr();
        $label->setAttr('for', $id);
        $ctrl->setAttr('id', $id);
        
        if ( in_array($ctrl->getAttr('type'), ['checkbox', 'radio']) ) { 
            $css =  $this->isInline($ctrl, 'inline') ? $this->theme['tickable_inline']['class'] . ' ' : '' .
                    $this->theme['tickable_wrap']['class'];
            $open = "<div class=\"{$css}\">";
            $close = '</div>';
        }
        else
            $open = $close = '';
            
        $result = $open;
        if ( $direction === 'label-first' )
            $result .= $label->render() . $ctrl->render();
        else
            $result .= $ctrl->render() . $label->render();
        $result .= $close;
        return $result;
    }    
    public function handleControl(Control $ctrl): string {
        $result = '';
        if ( $this->pendingLabel ) {
            $result = $this->pair($this->pendingLabel, $ctrl, 'label-first');
            $this->pendingLabel = null;
        } else {
            $this->pendingControl = $ctrl;
            $this->pairDirection = 'control-first';
        }
        return $result;
    }
    public function handleLabel(Control $label): string {
        $result = '';
        if ( $this->pendingControl ) {
            $result = $this->pair($label, $this->pendingControl, 'control-first');
            $this->pendingControl = null;
        } else {
            $this->pendingLabel = $label;
            $this->pairDirection = 'label-first';
        }
        return $result;
    }
}
