<?php

namespace App\Modules\Product\Actions;

use App\Modules\Product\Services\ProductService;

class ListProductsAction
{
    public function __construct(protected ProductService $service){}

    public function execute(bool $onlyActive = true)
    {
        return $onlyActive
            ? $this->service->available()
            : $this->service->all();
    }
}