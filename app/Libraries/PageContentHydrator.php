<?php 
namespace App\Libraries;

class PageContentHydrator {
    public function hydrate(array $rows): array {
        $page = [
            'page_id'   => $rows[0]->page_id,
            'url'       => $rows[0]->url,
            'view_file' => $rows[0]->view_file,
        ];
        $namespace = 'App\\Renderers\\';
        foreach ( $rows as $row ) {
            $classname = $namespace . $row->renderer_class;
            $page[$row->html_identifier] = new $classname($row);
        }
        return $page;
    }
}