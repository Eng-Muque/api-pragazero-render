<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Actions\ListProductsAction;
use App\Modules\Product\Actions\ShowProductAction;

class ProductController extends Controller
{
    public function index(ListProductsAction $action)
    {
        return response()->json(
            $action->execute()
        );
    }

    public function show(
        int $id,
        ShowProductAction $action
    ) {
        return response()->json(
            $action->execute($id)
        );
    }
}