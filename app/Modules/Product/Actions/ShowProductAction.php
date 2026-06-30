<?php

namespace App\Modules\Product\Actions;

use App\Modules\Product\Services\ProductService;

class ShowProductAction
{
    public function __construct(protected ProductService $service){}

    public function execute(int $id)
    {
        return $this->service->find($id);
    }
}