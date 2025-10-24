<?php

namespace App\Controllers;
use App\Libraries\FolderMenuBuilder;

function getFolderTree(string $basePath): array {
    $result = [];

    // Ensure trailing slash
    $basePath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    // Get immediate subdirectories
    $dirs = glob($basePath . '*', GLOB_ONLYDIR);

    foreach ($dirs as $dir) {
        // Recursively get subdirectories
        $result[basename($dir)] = getFolderTree($dir);
    }

    return $result;
}


class Home extends BaseController {
    public function index(): string {
        $viewData = [];
        //helper('filesystem');
        
        $folderTree = getFolderTree('E:/CI4-sites/cms-site/writable/uploads/');
        $builder = new FolderMenuBuilder('');
        $viewData['media_menu'] = $builder->render($folderTree);

        /*$items = [
            (object)['url' => '/',        'text' => 'Home'],
            (object)['url' => '/testing',   'text' => 'Testing'],
            (object)['url' => '/contact', 'text' => 'Contact'],
        ];

        $menu = new \App\Libraries\MenuBuilder($items, [
            'ul' => 'nav nav-pills',
            'li' => 'nav-item',
            'a'  => 'nav-link',
        ], '/about');

        $viewData['footerMenu'] = $menu;*/
        //return view('welcome_message', $viewData);
        return view('media_manager', $viewData);
    }
}
