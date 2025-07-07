<?php

namespace Core\Controllers;

use Core\Models\HTMLModel;
use Core\lib\HTMLIterator;

class MainController
{
    private $twig;


    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function indexAction()
    {
        $resultMeta = [];
        $htmlModel = new HTMLModel();
        $iterator = new HTMLIterator($htmlModel->getHTML());
        foreach ($iterator as $meta) {
            $resultMeta[$meta['type']] = $meta['content'];
        }

        echo $this->twig->render('index.twig', [
            'title' => htmlspecialchars($resultMeta['title'], ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars($resultMeta['description'], ENT_QUOTES, 'UTF-8'),
            'keywords' => htmlspecialchars($resultMeta['keywords'], ENT_QUOTES, 'UTF-8'),
        ]);
    }

    public function removerTagsAction()
    {
        $htmlModel = new HTMLModel();
        $remover = new HTMLIterator($htmlModel->getHTML());
        $cleanedHtml = $remover->removeMetaTags();
        echo $this->twig->render('newindex.twig', [
            'html' => $cleanedHtml,
        ]);
    }

}