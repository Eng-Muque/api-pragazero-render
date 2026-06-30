<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderManagementController extends Controller
{
    // Listar todos os orçamentos de todos os clientes
    public function listQuotations() {
        return Quotation::with(['user', 'service'])->latest()->get();
    }

    // Definir o preço e enviar para o cliente
    public function updateQuotationPrice(Request $request, $id) {
        $request->validate(['offered_price' => 'required|numeric']);
        
        $quotation = Quotation::findOrFail($id);
        $quotation->update([
            'offered_price' => $request->offered_price,
            'status' => 'orcamento_enviado'
        ]);

        return response()->json(['message' => 'Orçamento atualizado e enviado ao cliente!']);
    }
        // Listar todas as vendas da loja
    public function listOrders() {
        return Order::with(['user', 'items.product'])->latest()->get();
    }

    // Confirmar que o pagamento foi recebido
    public function updateStatus(Request $request, $id) 
    {
        // Valida se o status enviado é um dos permitidos
        $request->validate([
            'status' => 'required|in:pendente,pago,cancelado'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status atualizado com sucesso!',
            'status' => $order->status
        ]);
    }
    public function destroy($id) 
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete(); // Isso apaga o pedido e os itens (se houver cascade no banco)
            return response()->json(['message' => 'Pedido removido com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao eliminar pedido'], 500);
        }
    }
    public function destroyQuotation($id) 
    {
        try {
            $quotation = Quotation::findOrFail($id);
            $quotation->delete();
            
            return response()->json(['message' => 'Orçamento eliminado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Não foi possível eliminar o orçamento'], 500);
        }
    }

}
