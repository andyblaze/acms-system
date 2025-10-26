<?php

namespace App\Controllers;


class Home extends BaseController {
    public function index(): string {

        $this->viewData += ['meta_description'=>'A CMS'];
        //dd($this->viewData);
        $items = [
            (object)['url' => '/',         'text' => 'Home'],
            (object)['url' => '/features', 'text' => 'Features'],
            (object)['url' => '/contact',  'text' => 'Contact']
        ];
        $menu = new \App\Libraries\MenuBuilder($items, [
            'ul' => 'navbar-nav',
            'li' => 'nav-item',
            'a'  => 'nav-link',
        ], '/');
        $this->viewData['mainMenu'] = $menu->render();
        
        $menu->setItems([
            (object)['url' => '/accessibility', 'text' => 'Accessibility'],
            (object)['url' => '/testing',       'text' => 'Testing'],
            (object)['url' => '/contact',       'text' => 'Contact']
        ]);

        $menu->setClasses([
            'ul' => 'nav nav-pills',
            'li' => 'nav-item',
            'a'  => 'nav-link',
        ]);
        
        $menu->setActive('/');

        $this->viewData['footerMenu'] = $menu->render();
        return view('pages/' . $this->viewData['view_file'], $this->viewData);
    }
}
