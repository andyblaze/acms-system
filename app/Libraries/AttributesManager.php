<?php
namespace App\Libraries;

class AttributesManager {
    protected $attrStr = '';
    protected $attrArray = [];
    protected $errors = [];
    
    public function initAttributes(string|array $attrs, bool $addId=true) {
        if ( is_string($attrs) ) {
            if ( $this->test($attrs) === false ) {
                $errs = implode(', ', $this->errors);
                $this->showError("Error - Attribute string is invalid:\n {$errs}\n {$attrs}");
                $this->errors = [];
            }
            else {
                $this->attrStr = $attrs;
                $this->attrArray = $this->attsToArray($attrs);
            }
        }
        if ( is_array($attrs) ) {
            $this->attrArray = $attrs;
        }
        if ( $addId && ! $this->has('id') )
            $this->attrArray['id'] = 'id_' . randomStr();
        return $this;
    }
    protected function showError($data) {
        $err = is_string($data) ? $data : print_r($data, true);
        echo "<pre>{$err}</pre>";
    }   
    protected function validChars(string $str) {
        return preg_match('/[^a-zA-Z0-9_\-\s="]/', $str) === 0;    
    }
    protected function str_all_pos(string $haystack, string $needle): array {
        $positions = [];
        $offset = 0;
        while (($pos = strpos($haystack, $needle, $offset)) !== false) {
            $positions[] = $pos;
            $offset = $pos + strlen($needle);
        }
        return $positions;
    }
    protected function test(string $str) {
        $ok = true;
        $atts = $this->normalise_string($str);
        if (count($this->str_all_pos($atts, '"')) % 2 !== 0) {
            $this->errors[] = 'Mismatched quotes';
            $ok = false;
        }
        if ( ! $this->validChars($atts) ) {
            $this->errors[] = 'Invalid characters';
            $ok = false;
        }
        return $ok;
    }    
    protected function strip_whitespace(string $str) {
        return preg_replace('!\s+!', ' ', str_replace(["\r", "\n", "\t"], '', trim($str)));
    }		
    protected function normalise_string(string $str) {
        $result = $this->strip_whitespace($str);
        $result = str_replace(['= ', ' =', '=" ', ' "'], ['=', '=', '="', '"'], $result);
        return $result;
    }
    public function attsToArray(string $str) {
        $trm = function($str, $chars='" ') { return trim($str, $chars); };
        $arr = preg_split('/\s+(?=(?:[^"]*"[^"]*")*[^"]*$)/', $str);
        $atts = [];
        foreach ( $arr as $a ) {
            if ($a === '') continue;
            if ( strpos($a, '=') !== false ) {
                [$k, $v] = explode('=', $a, 2);
                $atts[$trm($k)] = $trm($v);
            }
            else {
                $atts[$trm($a)] = $trm($a);
            }
        }
        return $atts;
    }
    public function merge($attrs) {
        foreach ( $attrs as $k=>$v )
            $this->attrArray[$k] = $v;
    }
    protected function mergeClass(string $cls) {
        if ( array_key_exists('class', $this->attrArray) )
            $this->attrArray['class'] .= " {$cls}";
        else 
            $this->attrArray['class'] = $cls;
    }
    public function attsToStr() {
        $str = '';
        foreach ( $this->attrArray as $k=>$v )
            $str .= empty($k) ? '' : "{$k}=\"{$v}\" ";
        $this->attrArray = [];
        $this->attrStr = '';
        return trim($str);
    }
    public function addClass(string $cls) {
        $this->mergeClass($cls);
        return $this;
    }
    public function has($attr) {
        return array_key_exists($attr, $this->attrArray);
    }
    public function get($attr) {
        return $this->has($attr) ? $this->attrArray[$attr] : null;
    }
    public function toArray() {
        $arr = $this->attrArray;
        $this->attrArray = [];
        return $arr;
    }
    public function toString() {
        return $this->attsToStr();
    }
}