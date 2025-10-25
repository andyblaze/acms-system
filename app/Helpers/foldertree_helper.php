<?php
function getFolderTree(string $basePath): array {
    $result = [];
    // Ensure trailing slash
    $basePath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $dirs = glob($basePath . '*', GLOB_ONLYDIR);
    foreach ($dirs as $dir) {
        $result[basename($dir)] = getFolderTree($dir);
    }
    return $result;
}