<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Actions\ListProductsAction;
use App\Modules\Product\Actions\ShowProductAction;
use App\Modules\Product\Actions\CreateProductAction;
use App\Modules\Product\Actions\UpdateProductAction;
use App\Modules\Product\Actions\ActivateProductAction;
use App\Modules\Product\Actions\DeactivateProductAction;
use App\Modules\Product\Requests\StoreProductRequest;
use App\Modules\Product\Requests\UpdateProductRequest;

class AdminProductController extends Controller
{
    public function index(
        ListProductsAction $action
    ) {
        return response()->json(
            $action->execute(false)
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

    public function store(
        StoreProductRequest $request,
        CreateProductAction $action
    ) {
        return response()->json(
            $action->execute($request),
            201
        );
    }

    public function update(UpdateProductRequest $request,int $id,UpdateProductAction $action) 
    {
        return response()->json(
            $action->execute($id, $request)
        );
    }

    public function activate(
        int $id,
        ActivateProductAction $action
    ) {
        return response()->json(
            $action->execute($id)
        );
    }

    public function deactivate(int $id, DeactivateProductAction $action) {
        return response()->json(
            $action->execute($id)
        );
    }
    public function toggleStatus($id, DeactivateProductAction $action)
    {   
        return response()->json($action->execute($id));
    }
}