<?php

namespace Tests\Integration;

use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Thread\ThreadCreateDTO;
use ChatbotPhp\DTO\Thread\ThreadContinueDTO;

class ThreadIntegrationTest extends BaseIntegrationTestCase
{
    private static ?string $createdAssistantId = null;
    private static ?string $createdConversationId = null;

    public function testCreateThread(): void
    {
        $contextDto = new ContextCreateDTO("Contexte de test pour thread");
        $contextResult = $this->chatbotClient->createContext($contextDto);
        $contextData = $this->assertSuccessfulResponse($contextResult);

        $this->assertIsArray($contextData['data']);
        $this->assertArrayHasKey('contextId', $contextData['data']);
        $this->assertIsString($contextData['data']['contextId']);

        $contextId = $contextData['data']['contextId'];
        $assistantDto = new AssistantCreateDTO($contextId, []);
        $assistantResult = $this->chatbotClient->createAssistant($assistantDto);
        $assistantData = $this->assertSuccessfulResponse($assistantResult);

        $this->assertIsArray($assistantData['data']);
        $this->assertArrayHasKey('assistantId', $assistantData['data']);
        $this->assertIsString($assistantData['data']['assistantId']);

        self::$createdAssistantId = $assistantData['data']['assistantId'];

        $threadDto = new ThreadCreateDTO(
            self::$createdAssistantId,
            'Peux-tu m\'aider à documenter cette fonction PHP ?'
        );

        $result = $this->chatbotClient->createThread($threadDto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('conversationId', $responseData['data']);
        $this->assertArrayHasKey('assistantMessage', $responseData['data']);
        $this->assertNotEmpty($responseData['data']['conversationId']);

        $this->assertIsString($responseData['data']['conversationId']);
        $this->assertStringStartsWith('con_', $responseData['data']['conversationId']);
        $this->assertIsString($responseData['data']['assistantMessage']);

        self::$createdConversationId = $responseData['data']['conversationId'];
    }

    public function testContinueThread(): void
    {
        if (self::$createdConversationId === null) {
            $this->markTestSkipped('No thread created in previous test');
        }

        $conversationId = self::$createdConversationId;
        $message = 'Peux-tu m\'expliquer plus en détail ?';

        $dto = new ThreadContinueDTO($conversationId, $message);

        $result = $this->chatbotClient->continueThread($dto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('conversationId', $responseData['data']);
        $this->assertArrayHasKey('message', $responseData['data']);

        $this->assertIsString($responseData['data']['conversationId']);
        $this->assertIsString($responseData['data']['message']);
    }
}
