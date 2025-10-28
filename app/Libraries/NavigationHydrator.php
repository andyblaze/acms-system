<?php 
namespace App\Libraries;

class NavigationHydrator {
    public function hydrate(array $rows): array {
        // Hydrate into structured array grouped by menu
        $menus = [];

        foreach ( $rows as $row ) {
            $menuName = $row->name;
            if ( ! isset($menus[$menuName]) ) {
                $menus[$menuName] = (object)[
                    'menu_id' => $row->menu_id,
                    'items'   => [],
                ];
            }

            $menus[$menuName]->items[] = (object)[
                'item_id'    => $row->id,
                'label'      => $row->label,
                'url'        => $row->url,
                'parent_id'  => $row->parent_id
            ];
        }
        return $menus;
    }
}
