<?php

namespace App\Libraries;

/**
 * FolderMenuBuilder
 *
 * Builds a nested <ul><li> tree menu from a recursive folder array.
 * Each folder node includes:
 *   - data-url for AJAX loading
 *   - classes for open/closed visual states
 */
class FolderMenuBuilder
{
    protected string $baseUrl;
    protected string $ulClass;
    protected string $liClass;
    protected string $spanClass;

    /**
     * @param string $baseUrl  The base AJAX URL (e.g. "/media/browse")
     * @param array  $options  Optional CSS class overrides
     */
    public function __construct(string $baseUrl, array $options = [])
    {
        $this->baseUrl   = rtrim($baseUrl, '/');
        $this->ulClass   = $options['ulClass']   ?? 'folder-tree';
        $this->liClass   = $options['liClass']   ?? 'folder closed';
        $this->spanClass = $options['spanClass'] ?? 'folder-name';
    }

    /**
     * Render the entire menu as HTML
     *
     * @param array  $tree       Recursive folder array
     * @param string $parentPath Internal use: accumulated folder path
     * @param bool   $isRoot     Internal use: true for top level
     * @return string            The rendered HTML
     */
    public function render(array $tree, string $parentPath = '', bool $isRoot = true): string
    {
        if (empty($tree)) {
            return '';
        }

        // Root gets main UL class, subtrees get "subtree hidden"
        $ulClass = $isRoot ? $this->ulClass : 'subtree hidden';
        $html = "<ul class=\"{$ulClass}\">\n";

        foreach ($tree as $folder => $subtree) {
            // Build current full path (e.g. /images/holidays)
            $currentPath = ltrim($parentPath . '/' . $folder, '/');
            $dataUrl = htmlspecialchars($this->baseUrl . '/' . $currentPath);
            $folderName = htmlspecialchars($folder);

            $html .= "  <li class=\"{$this->liClass}\" data-url=\"{$dataUrl}\">\n";
            $html .= "    <span class=\"{$this->spanClass}\">{$folderName}</span>\n";

            // Recursively render subfolders
            if (!empty($subtree)) {
                $html .= $this->render($subtree, $currentPath, false);
            } else {
                // Always include empty subtree for consistency
                $html .= "    <ul class=\"subtree hidden\"></ul>\n";
            }

            $html .= "  </li>\n";
        }

        $html .= "</ul>\n";
        return $html;
    }
}