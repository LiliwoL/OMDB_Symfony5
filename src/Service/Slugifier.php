<?php

namespace App\Service;


class Slugifier
{
    public function slugify( string $string ): string
    {
        // Génération du slug
        // hello how are you ===> hello-how-are-you
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }
}