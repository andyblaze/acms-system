<?php
namespace App\Models;

use CodeIgniter\Model;

class PageModel extends Model {
    protected $table = 'pages';
    protected $returnType = 'object';

    public function pageDataByUrl(string $url): ?array {
        $rows = $this->db->table('pages p')
            ->select('p.url, pt.view_file, tc.text_key, tc.content, 
                mc.path, mc.type, mc.title, mc.alt_text, mc.media_key,
                tc.format, cm.page_id, cm.text_content_id, cm.media_content_id')
            ->join('page_templates pt', 'p.template_id = pt.id', 'left')
            ->join('content_map cm', 'p.id = cm.page_id', 'left')
            ->join('text_content tc', 'tc.id = cm.text_content_id', 'left')
            ->join('media_content mc', 'mc.id = cm.media_content_id', 'left')
            ->where('p.url', $url)
            ->get()
            ->getResult();
            
        if ( empty($rows) ) {
            return null;
        }
        // Base page object from first row
        $page = [
            'page_id'   => $rows[0]->page_id,
            'url'       => $rows[0]->url,
            'view_file' => $rows[0]->view_file
        ];

        // Populate content
        foreach ( $rows as $row ) {
            if ( ! empty($row->text_key) ) {
                $page[$row->text_key] = (object)[
                    'type'    => 'text',
                    'format'  => $row->format,
                    'content' => $row->content
                ];
            }

            if ( ! empty($row->media_key) ) {
                $page[$row->media_key] = (object)[
                    'type'     => $row->type,
                    'path'     => $row->path,
                    'title'    => $row->title,
                    'alt_text' => $row->alt_text
                ];
            }
        }
        return $page;
    }
}
