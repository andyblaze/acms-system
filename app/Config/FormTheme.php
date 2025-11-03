<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class FormTheme extends BaseConfig {
    public array $bootstrap = [
        'input'    => ['class' => 'form-control'],
        'textarea' => ['class' => 'form-control'],
        'checkbox' => ['class' => 'form-check-input'],
        'radio'    => ['class' => 'form-check-input'],
        'select'   => ['class' => 'form-select'],
        'label'    => ['class' => 'form-label'],
    ];
}
