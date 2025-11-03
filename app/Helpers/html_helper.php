<?php
function meta($name, $content) {
    return '<meta name="' . esc($name) . '" content="' . esc($content) . '">' . "\n";
}
function pre($data) {
    echo '<pre>'; var_dump($data); echo '</pre>';
}