<?php

namespace App\Modules\Product\Actions;

use App\Modules\Product\Services\ProductService;
use App\Modules\Product\Requests\UpdateProductRequest;

class UpdateProductAction
{
    public function __construct(protected ProductService $service){}

    public function execute(int $id,UpdateProductRequest $request)
    {
        return $this->service->update($id,$request);
    }
}