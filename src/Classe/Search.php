<?php


namespace App\Classe;


use App\Entity\Category;


class Search
{
    /** @var string|null */
    public ?string $q = '';

    /** @var Category[]|$categories */
    public array $categories = [];
}