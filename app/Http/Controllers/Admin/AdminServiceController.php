<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminServiceController extends Controller
{
    // Listar todos os serviços cadastrados
    public function index()
    {
        return response()->json(Service::all(), 200);
    }

    // Criar um novo serviço para orçamento
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            // Validamos como imagem real (máx 2MB)
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048' 
        ]);

        if ($request->hasFile('image')) {
            // Guarda na pasta 'services' dentro de storage/app/public
            $path = $request->file('image')->store('services', 'public');
            $validated['image'] = $path;
        }

        $service = Service::create($validated);

        return response()->json([
            'message' => 'Serviço cadastrado com sucesso!',
            'service' => $service
        ], 201);
    }

    // Mostrar detalhes de um serviço específico
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service, 200);
    }

    // Atualizar dados de um serviço existente
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        // 1. Validar os dados (incluindo a imagem)
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Aceita imagem
        ]);

        // 2. Lógica de Upload da Imagem
        if ($request->hasFile('image')) {
            // Deletar a imagem antiga se existir (opcional, mas recomendado)
            if ($service->image && file_exists(storage_path('app/public/' . $service->image))) {
                \Storage::disk('public')->delete($service->image);
            }

            // Salvar a nova imagem na pasta 'services' dentro de storage/app/public
            $path = $request->file('image')->store('services', 'public');
            $validated['image'] = $path; // Atualiza o caminho para salvar no banco
        }

        // 3. Atualizar o banco de dados com os novos dados (incluindo o novo path da imagem)
        $service->update($validated);

        return response()->json([
            'message' => 'Serviço atualizado com sucesso!',
            'service' => $service
        ], 200);
    }


    // Remover um serviço do sistema
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['message' => 'Serviço removido com sucesso!'], 200);
    }
}
