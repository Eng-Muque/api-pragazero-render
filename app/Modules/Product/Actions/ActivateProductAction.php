<?php

namespace App\Modules\Product\Actions;

use App\Modules\Product\Services\ProductService;


class ActivateProductAction
{
    public function __construct(protected ProductService $service){}

    public function execute(int $id)
    {
        $this->service->toggleStatus($id);
    }
}