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
    private string $basePath = 'E:/CI4-sites/cms-site/writable/uploads/';
    public function index(): string {
        $viewData = [];
        //helper('filesystem');
        
        $folderTree = getFolderTree($this->basePath);
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
    public function files($dir=null) {
        //die($dir);
        $uri = service('uri'); // returns the current SiteURI instance.
        $segs = $uri->getSegments();
        $path = implode('/', array_slice($segs, 2));
        
        $targetDir = $this->basePath . $path;
        //die($targetDir);

        // Security check — make sure it stays within uploads
        if ( ! $targetDir || strpos($targetDir, realpath($this->basePath)) !== 0 ) {
            //return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid path']);
        }

        // Get files (non-recursive)
        $files = [];
        foreach (glob($targetDir . '/*') as $item) {
            if (is_file($item)) {
                $files[] = basename($item);
            }
        }

        return $this->response->setJSON(['files' => $files]);        
    }
}
