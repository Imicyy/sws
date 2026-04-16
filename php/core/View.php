<?php

declare(strict_types=1);

class View
{
    public static function render(string $view, array $data = []): void
    {
        $viewPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(500);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'View not found: ' . $view;
            exit;
        }

        extract($data, EXTR_SKIP);
        include $viewPath;
        exit;
    }
}
