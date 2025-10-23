<?php
namespace App\Libraries;

class MenuBuilder {
    protected array $items = [];
    protected string $ulClass = 'nav nav-pills';
    protected string $liClass = 'nav-item';
    protected string $aClass = 'nav-link';
    protected ?string $activeUrl = null;

    public function __construct(
        array $items = [],
        array $classes = [],
        ?string $activeUrl = null
    ) {
        $this->items = $items;

        // Optional overrides for classes
        $this->ulClass = $classes['ul'] ?? $this->ulClass;
        $this->liClass = $classes['li'] ?? $this->liClass;
        $this->aClass  = $classes['a']  ?? $this->aClass;

        $this->activeUrl = $activeUrl;
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
        if (empty($this->items)) {
            return '';
        }

        $html = "<ul class=\"{$this->ulClass}\">\n";

        foreach ($this->items as $item) {
            $url  = $item->url ?? '#';
            $text = $item->text ?? '';

            $isActive = ($this->activeUrl && $url === $this->activeUrl);
            $aClass   = $this->aClass . ($isActive ? ' active' : '');
            $attributes = ['class' => $aClass];

            $html .= "  <li class=\"{$this->liClass}\">\n";
            $html .= "    " . anchor($url, $text, $attributes) . "\n";
            $html .= "  </li>\n";
        }

        $html .= "</ul>\n";
        return $html;
    }

    public function __toString(): string {
        return $this->render();
    }
}
