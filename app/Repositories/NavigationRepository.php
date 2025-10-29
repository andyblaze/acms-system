<?php

namespace App\Repositories;

use CodeIgniter\Model;
use App\Hydrators\NavigationHydrator;

class NavigationRepository extends Model {
    protected $table = 'pages';
    protected $returnType = 'object';

    /**
     * Get all menus and their items, ordered correctly.
     */
    public function getMenus(): array {
        $rows = $this->db->table('pages p')
            ->select('p.url, m.name, mi.id, mi.menu_id, mi.page_id, mi.label, mi.parent_id')
            ->join('menu_items mi', 'p.id = mi.page_id', 'left')
            ->join('menus m', 'mi.menu_id = m.id', 'left')
            ->orderBy('m.id, mi.sort_order')
            ->get()
            ->getResult();

        $hydrator = new NavigationHydrator();
        return $hydrator->hydrate($rows);
    }
}
