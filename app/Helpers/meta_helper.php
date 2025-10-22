<?php
function meta($name, $content) {
    return '<meta name="' . esc($name) . '" content="' . esc($content) . '">' . "\n";
}