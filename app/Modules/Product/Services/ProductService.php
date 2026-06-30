<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Modules\Product\Requests\StoreProductRequest;
use App\Modules\Product\Requests\UpdateProductRequest;
use Exception;

class ProductService
{
    /**
     * Lista todos os produtos.
     */
    public function all()
    {
        return Product::orderByDesc('active')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Lista apenas produtos disponíveis.
     */
    public function available()
    {
        return Product::where('active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();
    }

    /**
     * Busca um produto.
     */
    public function find(int $id): Product
    {
        return Product::findOrFail($id);
    }

    /**
     * Cria um novo produto.
     */
    public function create(StoreProductRequest $request): Product
    {
        DB::beginTransaction();

        try {

            $data = $request->only([
                'name',
                'description',
                'categoria',
                'subcategoria',
                'price',
                'stock'
            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadImage($request);
            }

            $product = Product::create($data);

            DB::commit();

            return $product;

        } catch (Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Atualiza um produto.
     */
    public function update(int $id, UpdateProductRequest $request): Product
    {
        DB::beginTransaction();

        try {

            $product = $this->find($id);

            $data = $request->only([
                'name',
                'description',
                'categoria',
                'subcategoria',
                'price',
                'stock'
            ]);

            if ($request->hasFile('image')) {

                $this->deleteImage($product);

                $data['image'] = $this->uploadImage($request);
            }

            $product->update($data);

            DB::commit();

            return $product->fresh();

        } catch (Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Remove um produto.
     */
    public function toggleStatus(int $id): Product
    {
        $product = $this->find($id);

        $product->active = !$product->active;
        $product->save();

        return $product->fresh();
    }

    /**
     * Faz upload da imagem.
     */
    private function uploadImage(Request $request): string
    {
        return $request
            ->file('image')
            ->store('products', 'public');
    }

    /**
     * Remove imagem antiga.
     */
    private function deleteImage(Product $product): void
    {
        if (!$product->image) {
            return;
        }

        if (Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
    }
}