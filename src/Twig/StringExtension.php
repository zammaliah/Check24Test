<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StringExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('cutText', [$this, 'cutText']),
        ];
    }

    public function cutText(String $string): string
    {
        if(strlen($string) <= 1000)
            return $string;

        $string = substr($string, 0, 1000);
        $pos = max ([strripos($string, '.'), strripos($string, '?'), strripos($string, '!')]);
        $string = substr($string, 0, $pos);

        return $string.'...';
    }
}