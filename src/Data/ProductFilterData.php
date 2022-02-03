<?php

namespace App\Data;

use App\Entity\Category;

class ProductFilterData
{
    /**
     * @var string|null
     */
    public ?string $search = null;

    /**
     * @var Category[]
     */
    public $categories;
}
