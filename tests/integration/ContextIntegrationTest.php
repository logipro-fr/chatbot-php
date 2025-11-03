<?php

namespace Tests\Integration;

use ChatbotPhp\DTO\Context\ContextCreateDTO;

class ContextIntegrationTest extends BaseIntegrationTestCase
{
    public function testCreateContext(): void
    {
        $contextMessage = "Test d'intégration - Création de contexte";
        $dto = new ContextCreateDTO($contextMessage);

        $result = $this->chatbotClient->createContext($dto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('contextId', $responseData['data']);
        $this->assertNotEmpty($responseData['data']['contextId']);

        $this->assertIsString($responseData['data']['contextId']);
        $this->assertStringStartsWith('cot_', $responseData['data']['contextId']);
    }
}
