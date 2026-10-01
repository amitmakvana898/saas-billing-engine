<?php

namespace App\Core;

class View
{
    public static function render(string $template, array $data = [], ?string $layout = 'main'): string
    {
        extract($data);

        $viewFile = __DIR__ . '/../../views/' . str_replace('.', '/', $template) . '.php';
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View file not found: {$viewFile}");
        }

        // Buffer the content of the specific view
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // If no layout is requested, return content directly
        if ($layout === null) {
            return $content;
        }

        $layoutFile = __DIR__ . '/../../views/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            throw new \RuntimeException("Layout file not found: {$layoutFile}");
        }

        // Buffer the layout with $content injected
        ob_start();
        require $layoutFile;
        return ob_get_clean();
    }
}