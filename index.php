<?php
require_once './HTMLIterator.php';
// Маршрутизация
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$GLOBALS['request'] = $request;


switch ($request) {
    case '/':
    case '/home':
        $resultMeta = [];
        // Создание нового объекта DOMDocument
        $htmlData = file_get_contents('./remover.html');

        $iterator = new HTMLIterator($htmlData);
        foreach ($iterator as $meta) {
            $resultMeta[$meta['type']] = $meta['content'];
        }

        echo $resultMeta['title'] . '<br>';
        echo $resultMeta['description'] . '<br>';
        echo $resultMeta['keywords'] . '<br>';
        break;
    case '/remove':
        // Создание нового объекта DOMDocument
        $htmlData = file_get_contents('./remover.html');
        $htmlModel = new DOMDocument();

        $remover = new HTMLIterator($htmlData);
        $cleanedHtml = $remover->removeMetaTags();
        echo $cleanedHtml;
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        echo '404';
        break;
}
