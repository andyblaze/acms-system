<?php

namespace App\Libraries\Form;

class FormSecurity {
    protected string $key;

    public function __construct() {
        $this->key = env('formKey');
    }

    /**
     * Generate a nonce and checksum for a given field list.
     */
    public function secure(array $fields): array {
        sort($fields); // ensures stable order
        $fieldsStr = implode(',', $fields);
        $nonce = randomStr();
        $checksum = hash_hmac('sha256', $fieldsStr . '|' . $nonce, $this->key);

        return [
            'fields'   => $fieldsStr,
            'nonce'    => $nonce,
            'checksum' => $checksum,
        ];
    }

    /**
     * Validate checksum using submitted values.
     */
    public function check(string $fields, string $nonce, string $checksum): bool {
        $expected = hash_hmac('sha256', $fields . '|' . $nonce, $this->key);
        return hash_equals($expected, $checksum);
    }
}
