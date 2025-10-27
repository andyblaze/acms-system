<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Menus extends BaseConfig {
    public array $menuClasses = [
        'mainMenu' => [
            'ul' => 'navbar-nav',
            'li' => 'nav-item',
            'a'  => 'nav-link'
        ],
        'footMenu' => [
            'ul' => 'nav nav-pills',
            'li' => 'nav-item',
            'a'  => 'nav-link'
        ]
    ];
}
