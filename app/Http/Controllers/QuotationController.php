<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuotationController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validação manual para garantir resposta JSON em caso de erro
        $validator = Validator::make($request->all(), [
            'service_id'   => 'required|exists:services,id',
            'client_notes' => 'required|string|min:10',
        ], [
            'service_id.exists' => 'O serviço selecionado não está disponível no nosso catálogo.',
            'required'          => 'O campo :attribute é obrigatório.',
            'min'               => 'As notas do cliente devem ter pelo menos :min caracteres.'
        ]);

        // 2. Verifica se a validação falhou e retorna 422
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro de validação nos dados enviados.',
                'errors'  => $validator->errors()
            ], 422);
        }

        // 3. Tenta criar o orçamento
        try {
            $quotation = Quotation::create([
                'user_id'      => auth()->id(), // Certifique-se que o usuário está logado
                'service_id'   => $request->service_id,
                'client_notes' => $request->client_notes,
                'status'       => 'pendente'
            ]);

            return response()->json([
                'message'   => 'Solicitação de orçamento enviada com sucesso!',
                'quotation' => $quotation
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao processar a solicitação no servidor.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    
    public function updateQuotationPrice(Request $request, $id) {
    $request->validate(['offered_price' => 'required|numeric']);
    
    $quotation = Quotation::findOrFail($id);
    $quotation->update([
        'offered_price' => $request->offered_price,
        'status' => 'orcamento_enviado' // Status automático ao definir preço
    ]);

    return response()->json(['message' => 'Sucesso!']);
    }

    public function myQuotations()
    {
        try {
            $quotations = Quotation::with(['service'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

            return response()->json($quotations, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar orçamentos.',
                'error'   => $e->getMessage()
            ], 500);
        }
}

}

