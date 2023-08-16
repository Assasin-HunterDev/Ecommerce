<?php

namespace App\Models\Api;

/**
 * Class Product represents a product in the API namespace.
 *
 * @package App\Models\Api
 */
class Product extends \App\Models\Product
{
    /**
     * Get the route key name for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
