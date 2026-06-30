<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use App\Models\Quotation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ciclo_de_vida_do_orcamento_test()
    {
        // 1. PREPARAÇÃO (Criação de usuários e serviço)
        $admin = User::factory()->create(['role' => 'admin']);
        $cliente = User::factory()->create(['role' => 'customer']);
        $outroCliente = User::factory()->create(['role' => 'customer']);
        $servico = Service::create(['name' => 'Dedetização', 'description' => 'Serviço de controle de pragas']);

        // ---------------------------------------------------------
        // PROVA 1: CLIENTE SOLICITA ORÇAMENTO
        // ---------------------------------------------------------
        $responseRequest = $this->actingAs($cliente, 'sanctum')->postJson('/api/quotations', [
            'service_id' => $servico->id,
            'client_notes' => 'Preciso de orçamento para um armazém de 500m2.'
        ]);

        $responseRequest->assertStatus(201);
        echo "\n[PROVA 1] Orçamento solicitado pelo cliente:";
        dump($responseRequest->json());

        // ---------------------------------------------------------
        // PROVA 2: ADMIN LISTA TODAS AS SOLICITAÇÕES
        // ---------------------------------------------------------
        $responseAdminList = $this->actingAs($admin, 'sanctum')->getJson('/api/admin/quotations');
        
        $responseAdminList->assertStatus(200)->assertJsonCount(1);
        echo "\n[PROVA 2] Admin visualiza lista de solicitações:";
        dump($responseAdminList->json());

        // ---------------------------------------------------------
        // PROVA 3: ADMIN DEFINE O PREÇO (PATCH)
        // ---------------------------------------------------------
        $orcamentoId = $responseRequest->json('quotation_id');
        $responsePrice = $this->actingAs($admin, 'sanctum')->patchJson("/api/admin/quotations/{$orcamentoId}/price", [
            'offered_price' => 1500.00
        ]);

        $responsePrice->assertStatus(200);
        echo "\n[PROVA 3] Admin define preço de 1500.00:";
        dump($responsePrice->json());

        // ---------------------------------------------------------
        // PROVA 4: CLIENTE VÊ O SEU ORÇAMENTO ATUALIZADO
        // ---------------------------------------------------------
        $responseMyQuotes = $this->actingAs($cliente, 'sanctum')->getJson('/api/my-quotations');
        
        $responseMyQuotes->assertStatus(200);
        $this->assertEquals(1500.00, $responseMyQuotes->json('0.offered_price'));
        $this->assertEquals('orcamento_enviado', $responseMyQuotes->json('0.status'));
        echo "\n[PROVA 4] Cliente recebeu o preço e status mudou:";
        dump($responseMyQuotes->json());

        // ---------------------------------------------------------
        // PROVA 5: SEGURANÇA (OUTRO CLIENTE NÃO PODE VER O ORÇAMENTO DO PRIMEIRO)
        // ---------------------------------------------------------
        $responseUnauthorized = $this->actingAs($outroCliente, 'sanctum')->getJson('/api/my-quotations');
        
        $responseUnauthorized->assertStatus(200)->assertJsonCount(0);
        echo "\n[PROVA 5] Segurança confirmada: Outro cliente não viu o orçamento alheio (Lista vazia).";
    }
}
