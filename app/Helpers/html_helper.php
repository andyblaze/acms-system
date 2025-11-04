<?php
function meta($name, $content) {
    return '<meta name="' . esc($name) . '" content="' . esc($content) . '">' . "\n";
}
function pre($data) {
    echo '<pre>'; var_dump($data); echo '</pre>';
}
function randomStr() {
    $t = microtime(true);
    $s  = floor($t);
    $ms = (int)(($t - $s) * 1e6);
    $r  = substr(strtolower(base_convert(random_int(0, 36**12 - 1), 10, 36)), 0, 12);
    return
        str_pad(base_convert($s, 10, 36), 8, '0', STR_PAD_LEFT) .
        str_pad(base_convert($ms, 10, 36), 5, '0', STR_PAD_LEFT) .
        $r;
}