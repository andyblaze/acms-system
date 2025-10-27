<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\PageHydrator;

class PageModel extends Model {
    protected $table = 'pages';
    protected $returnType = 'object';

    public function pageDataByUrl(string $url): ?array {
        $rows = $this->db->table('pages p')
            ->select('p.url, pt.view_file, tc.content, 
                mc.path, mc.type, mc.title, mc.alt_text,
                tc.format, cm.page_id, cm.text_content_id, cm.media_content_id,
                rc.renderer_class, hi.html_identifier')
            ->join('page_templates pt', 'p.template_id = pt.id', 'left')
            ->join('content_map cm', 'p.id = cm.page_id', 'left')
            ->join('html_identifiers hi', 'hi.id = cm.html_identifier_id')
            ->join('text_content tc', 'tc.id = cm.text_content_id', 'left')
            ->join('media_content mc', 'mc.id = cm.media_content_id', 'left')
            ->join('renderer_classes rc', 'rc.id = cm.renderer_id', 'left')
            ->where('p.url', $url)
            ->get()
            ->getResult();
            
        if ( empty($rows) ) {
            return null;
        }
        $hydrator = new PageHydrator();
        return $hydrator->hydrate($rows);
    }
}
