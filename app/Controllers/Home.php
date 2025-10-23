<?php

namespace App\Controllers;

class Home extends BaseController {
    public function index(): string {
        $viewData = [];
        helper('filesystem');

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
