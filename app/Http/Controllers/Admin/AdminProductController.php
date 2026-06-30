<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    // Função para listar produtos (Opcional para o Admin)
    public function index()
    {
        return Product::all();
    }

    // ESTA É A FUNÇÃO QUE ESTAVA FALTANDO:
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        // Mudamos de 'string' para 'image' com limite de 2MB
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' 
    ]);

    // Verifica se uma imagem foi enviada
    if ($request->hasFile('image')) {
        // Guarda o arquivo na pasta 'products' (dentro de storage/app/public)
        $path = $request->file('image')->store('products', 'public');
        // Substituimos o objeto do arquivo pelo caminho da string para salvar no banco
        $validated['image'] = $path;
    }

    $product = Product::create($validated);

    return response()->json([
        'message' => 'Produto criado com sucesso!',
        'product' => $product
    ], 201);
}

    // Função para ver um produto específico
    public function show($id)
    {
        return Product::findOrFail($id);
    }

    // Função para atualizar produto (Dia 2)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return response()->json($product);
    }

    // Função para deletar produto (Dia 2)
    public function destroy($id)
{
    try {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Produto removido']);
    } catch (\Exception $e) {
        // Isso vai devolver o erro real para o teu console do navegador
        return response()->json([
            'error' => 'Erro ao eliminar',
            'details' => $e->getMessage()
        ], 500);
    }
}

}
