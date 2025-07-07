<?php

namespace Core\lib;

use ArrayIterator;
use DOMDocument;
use DOMXPath;
use Iterator;


class HTMLIterator implements Iterator
{
    private $position = 0;
    private $metaTags;
    private $document;

    public function __construct(string $html)
    {
        $this->document = new DOMDocument();
        @$this->document->loadHTML($html);
        $this->extractMetaTags();
    }

    private function extractMetaTags(): void
    {
        // Извлекаем title
        $titles = $this->document->getElementsByTagName('title');
        if ($titles->length > 0) {
            $this->metaTags[] = [
                'type' => 'title',
                'content' => $titles->item(0)->nodeValue
            ];
        }

        // Извлекаем meta-теги
        $metaTags = $this->document->getElementsByTagName('meta');
        foreach ($metaTags as $tag) {
            $name = $tag->getAttribute('name');
            if (in_array(strtolower($name), ['description', 'keywords'])) {
                $this->metaTags[] = [
                    'type' => $name,
                    'content' => $tag->getAttribute('content')
                ];
            }
        }
    }

    // Реализация методов интерфейса Iterator
    public function current(): array
    {
        return $this->metaTags[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->metaTags[$this->position]);
    }

    public function getIterator()
    {
        $tagsToRemove = [];

        // Находим все мета-теги для удаления
        $xpath = new DOMXPath($this->document);

        // 1. Удаляем title
        foreach ($this->document->getElementsByTagName('title') as $tag) {
            $tagsToRemove[] = $tag;
        }

        // 2. Удаляем meta description и keywords
        foreach ($xpath->query('//meta[@name="description" or @name="keywords"]') as $tag) {
            $tagsToRemove[] = $tag;
        }

        return new ArrayIterator($tagsToRemove);
    }

    public function removeMetaTags(): string
    {
        // Удаляем все найденные мета-теги
        foreach ($this->getIterator() as $tag) {
            $tag->parentNode->removeChild($tag);
        }

        // Возвращаем очищенный HTML
        return $this->document->saveHTML();
    }
}