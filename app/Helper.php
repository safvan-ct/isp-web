<?php

use Illuminate\Support\Facades\Storage;

function uploadFile($file, $path)
{
    Storage::disk('public')->put($path, file_get_contents($file));
}

function getFile($path, $dummy = 'img/logo.png')
{
    return Storage::disk('public')->exists($path) ? Storage::disk('public')->url($path) : asset($dummy);
}

function deleteFile($path)
{
    Storage::disk('public')->delete($path);
}

if (! function_exists('convertAsTitle')) {
    function convertAsTitle($string)
    {
        // Convert to Title Case
        $title = ucwords($string);

        // Very basic pluralization (naive approach)
        if (str_ends_with($title, 'y')) {
            // e.g., "Category" -> "Categories"
            $plural = substr($title, 0, -1) . 'ies';
        } elseif (str_ends_with($title, 's')) {
            // e.g., "Class" -> "Classes"
            $plural = $title . 'es';
        } else {
            // e.g., "Book" -> "Books"
            $plural = $title . 's';
        }

        return $plural;
    }
}

if (! function_exists('truncateHtml')) {
    function truncateHtml($text, $limit = 255, $end = '...')
    {
        $doc = new DOMDocument();
        @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $text);
        $total = 0;

        $traverse = function ($node) use (&$traverse, &$total, $limit, $end) {
            if ($node->nodeType === XML_TEXT_NODE) {
                $len = mb_strlen($node->nodeValue);
                if ($total + $len > $limit) {
                    $node->nodeValue = mb_substr($node->nodeValue, 0, $limit - $total) . $end;
                    while ($node->nextSibling) {
                        $node->parentNode->removeChild($node->nextSibling);
                    }
                    return false;
                }
                $total += $len;
            } else {
                foreach (iterator_to_array($node->childNodes) as $child) {
                    if ($traverse($child) === false) {
                        while ($child->nextSibling) {
                            $child->parentNode->removeChild($child->nextSibling);
                        }
                        return false;
                    }
                }
            }
            return true;
        };

        $traverse($doc->documentElement);
        return preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());
    }
}
