<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StringExtension extends AbstractExtension
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('truncate', [$this, 'truncateString']),
        ];
    }

    /**
     * Truncates the given string and adds `...` when the $string argument's length exceeds $size.
     *
     * @param string $string
     * @param int    $size
     *
     * @return string
     */
    public function truncateString(string $string, int $size): string
    {
        return \strlen($string) < $size ? $string : \mb_substr(\strip_tags($string), 0, $size) . '...';
    }
}
