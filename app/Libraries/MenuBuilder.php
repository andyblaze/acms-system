<?php
namespace App\Libraries;

class MenuBuilder {
    protected array $items = [];
    protected string $ulClass = 'nav nav-pills';
    protected string $liClass = 'nav-item';
    protected string $aClass = 'nav-link';
    protected ?string $activeUrl = null;

    public function __construct(array $items=[], array $classes=[], ?string $activeUrl=null) {
        $this->setItems($items);
        $this->setClasses($classes);
        $this->setActive($activeUrl);
    }
    public function setClasses($classes): static {
        // Optional overrides for classes
        $this->ulClass = $classes['ul'] ?? $this->ulClass;
        $this->liClass = $classes['li'] ?? $this->liClass;
        $this->aClass  = $classes['a']  ?? $this->aClass;
        return $this;
    }
    public function setItems(array $items): static {
        $this->items = $items;
        return $this;
    }
    public function setActive(?string $url): static {
        $this->activeUrl = $url;
        return $this;
    }
    public function render(): string {
        if ( empty($this->items) ) {
            return '';
        }
        $html = "<ul class=\"{$this->ulClass}\">\n";

        foreach ( $this->items as $item ) { //dd($item);
            $isActive = ($this->activeUrl && $item->url === $this->activeUrl);
            $attributes = $isActive ?  ['aria-current'=>'page'] : [];
            $aClass   = $this->aClass . ($isActive ? ' active' : '');
            $attributes['class'] = $aClass;

            $html .= "  <li class=\"{$this->liClass}\">\n";
            $html .= "    " . anchor($item->url, $item->label, $attributes) . "\n";
            $html .= "  </li>\n";
        }
        $html .= "</ul>\n";
        return $html;
    }
    public function __toString(): string {
        return $this->render();
    }
}
