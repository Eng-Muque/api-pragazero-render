<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeroBanner; // IMPORTANTE: Adicionado
use Illuminate\Support\Facades\Storage; // IMPORTANTE: Adicionado
class HeroController extends Controller
{
    public function index() {
    // Retorna apenas banners ativos ordenados
    return HeroBanner::where('ativo', true)
            ->orderBy('ordem', 'asc')
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'imagem' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'subtitulo' => 'nullable|string',
            'ordem' => 'nullable|integer'
        ]);

        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('banners', 'public');

            $banner = HeroBanner::create([
                'titulo' => $request->titulo,
                'subtitulo' => $request->subtitulo,
                'imagem_url' => $path, // CORREÇÃO: Guardamos apenas 'banners/nome.jpg' como no Produto
                'ordem' => $request->ordem ?? 0,
                'ativo' => true
            ]);

            return response()->json($banner, 201);
        }
        
        return response()->json(['message' => 'Imagem não enviada'], 400);
    }


    // HeroController.php

    // Listagem para o admin (vê tudo)
    public function adminIndex() {
        return HeroBanner::orderBy('ordem')->get();
    }

    // Alternar status
    public function toggleStatus($id) {
        $banner = HeroBanner::findOrFail($id);
        $banner->ativo = !$banner->ativo;
        $banner->save();

        return response()->json(['message' => 'Status atualizado', 'ativo' => $banner->ativo]);
    }

        // Eliminar
    public function destroy($id) {
        $banner = HeroBanner::findOrFail($id);
        
        // Como agora guardamos apenas 'banners/nome.jpg', o disk('public') já sabe onde procurar
        Storage::disk('public')->delete($banner->imagem_url);
        
        $banner->delete();
        return response()->json(['message' => 'Eliminado com sucesso']);
    }


    public function update(Request $request, $id)
    {
        $hero = HeroBanner::findOrFail($id);

        $validated = $request->validate([
            'titulo'    => 'required|string|max:255',
            'subtitulo' => 'nullable|string',
            'ordem'     => 'required|integer',
            'imagem'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Atualiza textos e ordem
        $hero->titulo = $request->titulo;
        $hero->subtitulo = $request->subtitulo;
        $hero->ordem = $request->ordem;

        // Lógica para nova imagem
        if ($request->hasFile('imagem')) {
            // Elimina a imagem antiga se existir
            if ($hero->imagem_url) {
                Storage::disk('public')->delete($hero->imagem_url);
            }

            // Guarda a nova
            $path = $request->file('imagem')->store('banners', 'public');
            $hero->imagem_url = $path;
        }

        $hero->save();

        return response()->json($hero);
    }

}
