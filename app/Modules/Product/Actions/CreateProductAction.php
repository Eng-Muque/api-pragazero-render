<?php

namespace App\Modules\Product\Actions;

use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Requests\StoreProductRequest;

class CreateProductAction
{
    public function __construct(
        protected ProductService $service
    ){}

    public function execute(
        StoreProductRequest $request
    )
    {
        return $this->service->create($request);
    }
}