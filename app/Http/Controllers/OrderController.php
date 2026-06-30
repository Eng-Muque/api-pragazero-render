<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    
    public function store(Request $request)
    {
        // Validação estrita
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,transferencia'
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $total = 0;
                
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'total_price' => 0,
                    'payment_method' => $request->payment_method,
                    'status' => 'pendente'
                ]);

                foreach ($request->items as $itemData) {
        // No seu foreach do OrderController:
    $product = Product::lockForUpdate()->find($itemData['product_id']);

    // 1. Limpeza: Remove TUDO exceto dígitos e a ÚLTIMA vírgula ou ponto
    $rawPrice = $product->price; 
    // Exemplo: "Kz 1.500,00" -> "1500,00"
    $cleanPrice = preg_replace('/[^\d,.]/', '', $rawPrice);

    // 2. Converte para formato decimal puro (1500.00)
    // Remove o ponto de milhar e troca a vírgula decimal por ponto
    if (strpos($cleanPrice, ',') !== false && strpos($cleanPrice, '.') !== false) {
        // Caso tenha os dois: 1.500,00
        $valorNumerico = (float) str_replace(',', '.', str_replace('.', '', $cleanPrice));
    } else {
        // Caso tenha só um deles ou nenhum
        $valorNumerico = (float) str_replace(',', '.', $cleanPrice);
    }

    // 3. Grava o item e soma ao total
    $order->items()->create([
        'product_id' => $product->id,
        'quantity'   => $itemData['quantity'],
        'price'      => $valorNumerico
    ]);

    $total += ($valorNumerico * $itemData['quantity']);

        
        $product->decrement('stock', $itemData['quantity']);
    }

    // 5. Atualiza o total da ordem (agora com valor real)
    $order->update(['total_price' => $total]);


                return response()->json([
                    'message' => 'Pedido finalizado com sucesso!',
                    'order'   => $order->load('items.product', 'user') 
                ], 201);
            });

            

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function myOrders()
    {
        try {
            $orders = Order::with(['items.product'])
                ->where('user_id', auth()->id())
                ->latest() // ordena do mais recente
                ->get();

            return response()->json($orders, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar pedidos.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /*public function store(Request $request)
    {
        // 1. Validação dos dados (Garante resposta JSON 422 se falhar)
        $validator = Validator::make($request->all(), [
            'items'              => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'payment_method'     => 'required|in:cash,transferencia'
        ], [
            'items.*.product_id.exists' => 'Um ou mais produtos selecionados não existem.',
            'payment_method.in'         => 'O método de pagamento deve ser cash ou transferencia.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        try {
            // 2. Iniciamos a transação para garantir que tudo ocorra ou nada ocorra
            return DB::transaction(function () use ($validated) {
                $total = 0;
                
                $order = Order::create([
                    'user_id'        => auth()->id(),
                    'total_price'    => 0,
                    'payment_method' => $validated['payment_method']
                ]);

                foreach ($validated['items'] as $itemData) {
                    // LockForUpdate impede que outro processo altere o estoque simultaneamente
                    $product = Product::lockForUpdate()->find($itemData['product_id']);

                    // 3. Validação de Estoque
                    if ($product->stock < $itemData['quantity']) {
                        // Lançar Exception faz o DB::transaction dar Rollback automaticamente
                        throw new \Exception("Estoque insuficiente para o produto: {$product->name}. Disponível: {$product->stock}");
                    }

                    // Cria o item do pedido
                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity'   => $itemData['quantity'],
                        'price'      => $product->price
                    ]);

                    $total += $product->price * $itemData['quantity'];
                    
                    // Decrementa o estoque
                    $product->decrement('stock', $itemData['quantity']);
                }

                // Atualiza o total final do pedido
                $order->update(['total_price' => $total]);

                return response()->json([
                    'message' => 'Pedido finalizado com sucesso!',
                    'order'   => $order->load('items') // Carrega os itens na resposta
                ], 201);
            });

        } catch (\Exception $e) {
            // Retorna o erro de estoque ou qualquer erro de banco com status 400
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }*/
}

