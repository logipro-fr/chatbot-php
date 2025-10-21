<?php

namespace Tests\Integration;

use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Assistant\AssistantUpdateDTO;
use ChatbotPhp\DTO\Assistant\AssistantViewDTO;

class AssistantIntegrationTest extends BaseIntegrationTestCase
{
    private static ?string $createdAssistantId = null;

    public function testCreateAssistant(): void
    {
        $contextId = 'cot_68e7aeb0a1f43';
        $fileIds = ['file-KNpZsP3NBAajzDETBqZQpX'];

        $dto = new AssistantCreateDTO($contextId, $fileIds);

        $result = $this->chatbotClient->createAssistant($dto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('assistantId', $responseData['data']);
        $this->assertArrayHasKey('externalAssistantId', $responseData['data']);
        $this->assertNotEmpty($responseData['data']['assistantId']);

        $this->assertIsString($responseData['data']['assistantId']);
        $this->assertStringStartsWith('ast_', $responseData['data']['assistantId']);

        self::$createdAssistantId = $responseData['data']['assistantId'];
    }

    public function testViewAssistant(): void
    {
        $assistantId = self::$createdAssistantId ?? 'ast_abc123';

        $dto = new AssistantViewDTO($assistantId);

        $result = $this->chatbotClient->viewAssistant($dto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('assistantId', $responseData['data']);
        $this->assertArrayHasKey('externalAssistantId', $responseData['data']);

        if (isset($responseData['data']['name'])) {
            $this->assertIsString($responseData['data']['name']);
        }

        if (isset($responseData['data']['instructions'])) {
            $this->assertIsString($responseData['data']['instructions']);
        }

        if (isset($responseData['data']['fileIds'])) {
            $this->assertIsArray($responseData['data']['fileIds']);
        }
    }

    public function testUpdateAssistant(): void
    {
        $assistantId = self::$createdAssistantId ?? 'ast_abc123';
        $fileIds = ['fil-abc123', 'fil-def456'];
        $dto = new AssistantUpdateDTO($assistantId, $fileIds);

        $result = $this->chatbotClient->updateAssistant($dto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('assistantId', $responseData['data']);

        if (isset($responseData['data']['fileIds'])) {
            $this->assertIsArray($responseData['data']['fileIds']);
        }
    }

    public function testDeleteAssistant(): void
    {
        $assistantId = self::$createdAssistantId ?? 'ast_68f638a37c2474.79919725';

        $dto = new AssistantDeleteDTO($assistantId);

        $result = $this->chatbotClient->deleteAssistant($dto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('assistantId', $responseData['data']);
        $this->assertArrayHasKey('externalAssistantId', $responseData['data']);
    }
}
