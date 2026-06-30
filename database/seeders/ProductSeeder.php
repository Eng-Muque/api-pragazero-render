<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\File as LaravelFile;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'SANI-PLUS (HWC-100D)', 'desc' => 'Detergente desinfetante ultra concentrado de caráter ácido para limpeza diária de loiças sanitárias.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Sanitários', 'img' => 'SANI-PLUS (HWC-100D).png'],
            ['name' => 'LIXIVI-ACTIV (DCM-20)', 'desc' => 'Produto clorado em espuma para limpeza de superfícies duras em casas de banho.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Sanitários', 'img' => 'LIXIVI-ACTIV (DCM-20).png'],
            ['name' => 'HIGIPLUS (DDC-100)', 'desc' => 'Detergente desinfetante virucida concentrado para limpeza profunda de pavimentos.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'HIGIPLUS (DDC-100).png'],
            ['name' => 'PAVI-PLUS MOONLIGHT (HLP-100M)', 'desc' => 'Detergente neutro ultra concentrado com bioálcool para pavimentos.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'PAVI-PLUS MOONLIGHT (HLP-100M).png'],
            ['name' => 'PAVI-PLUS EXOTIC (HLP-100E)', 'desc' => 'Detergente neutro ultra concentrado extra perfumado para pavimentos.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'PAVI-PLUS EXOTIC (HLP-100E).png'],
            ['name' => 'LAVANDER NEUTROQUAT (DDC-V)', 'desc' => 'Detergente desinfetante concentrado e neutro de dupla ação com aroma a lavanda.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'LAVANDER NEUTROQUAT (DDC-V).png'],
            ['name' => 'LEMON NEUTROQUAT (DDC-L)', 'desc' => 'Detergente desinfetante concentrado e neutro de dupla ação com aroma a limão.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'LEMON NEUTROQUAT (DDC-L).png'],
            ['name' => 'LAVENDER PAVWASH (HLP-V)', 'desc' => 'Limpa pavimentos com aroma a lavanda para limpeza profunda. pH neutro.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'LAVENDER PAVWASH (HLP-V).png'],
            ['name' => 'LEMON PAVWASH (HLP-L)', 'desc' => 'Limpa pavimentos com aroma a limão para limpeza profunda. pH neutro.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'LEMON PAVWASH (HLP-L).png'],
            ['name' => 'SUBLIME MOONLIGHT (HLP-M)', 'desc' => 'Limpa pavimentos bioálcool de secagem rápida, sem deixar manchas.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'SUBLIME MOONLIGHT (HLP-M).png'],
            ['name' => 'SUBLIME EXOTIC (HLP-E)', 'desc' => 'Limpa pavimentos premium com perfume sofisticado e remoção de gorduras.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'SUBLIME EXOTIC (HLP-E).png'],
            ['name' => 'LIMPAV (HLP-50)', 'desc' => 'Detergente desengordurante alcalino de baixa espuma para autolavadoras.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'LIMPAV (HLP-50).png'],
            ['name' => 'LIMPAVNEUT (HLP-NA)', 'desc' => 'Detergente neutro com baixa formação de espuma para autolavadoras.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'LIMPAVNEUT (HLP-NA).png'],
            ['name' => 'BECRILAR (HCA-80)', 'desc' => 'Cera acrílica de alto rendimento para tratamento e brilho de pavimentos.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Pavimentos', 'img' => 'BECRILAR (HCA-80).png'],
            ['name' => 'SUBLIME-WOOD PROTECT (HLP-W)', 'desc' => 'Produto para limpeza profunda de superfícies e pavimentos de madeira.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Madeira', 'img' => 'SUBLIME-WOOD PROTECT (HLP-W).png'],
            ['name' => 'BEWOOD (HCM-50)', 'desc' => 'Cera acrílica adequada para polimento e proteção de pavimentos de madeira.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Madeira', 'img' => 'BEWOOD (HCM-50).png'],
            ['name' => 'MILTIPLUS (HMU-100P)', 'desc' => 'Multisuperfícies ultra concentrado neutro para limpeza de vidros e espelhos.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Geral', 'img' => 'MILTIPLUS (HMU-100P).png'],
            ['name' => 'OXIRAPID (PAB-100)', 'desc' => 'Desinfetante de superfícies pronto a usar com peróxido de hidrogénio.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Geral', 'img' => 'OXIRAPID (PAB-100).png'],
            ['name' => 'GELCLOR (DDD-P)', 'desc' => 'Detergente clorado desinfetante em gel à base de hipoclorito de sódio.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Geral', 'img' => 'GELCLOR (DDD-P).png'],
            ['name' => 'ALCORAPID (DAR-80)', 'desc' => 'Desinfetante de base alcoólica pronto a usar. Não necessita enxaguamento.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Geral', 'img' => 'ALCORAPID (DAR-80).png'],
            ['name' => 'ACTIVIFORT (DVF-80)', 'desc' => 'Detergente desinfetante virucida concentrado para contacto alimentar.', 'cat' => 'Limpeza em Superfície', 'sub' => 'Geral', 'img' => 'ACTIVIFORT (DVF-80).png'],
        ];

        foreach ($products as $p) {
            $sourcePath = database_path('seeders/images/products/' . $p['img']);

            if (File::exists($sourcePath)) {
                $newPath = Storage::disk('public')->putFile('products', new LaravelFile($sourcePath));

                Product::create([
                    'name'         => $p['name'],
                    'categoria'    => $p['cat'],
                    'subcategoria' => $p['sub'],
                    'description'  => $p['desc'],
                    'price'        => 0.00,
                    'stock'        => 0,
                    'image'        => $newPath,
                ]);
            } else {
                $this->command->error("Imagem não encontrada localmente: " . $p['img']);
            }
        }
    }
}