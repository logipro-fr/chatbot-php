<?php

namespace Tests\Integration;

use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Conversation\ConversationMakeDTO;
use ChatbotPhp\DTO\Conversation\ConversationContinueDTO;
use ChatbotPhp\DTO\Conversation\ConversationViewDTO;

class ConversationIntegrationTest extends BaseIntegrationTestCase
{
    private static ?string $createdConversationId = null;
    private static ?string $createdContextId = null;

    public function testMakeConversation(): void
    {
        $contextDto = new ContextCreateDTO("Contexte de test pour conversation");
        $contextResult = $this->chatbotClient->createContext($contextDto);
        $contextData = $this->assertSuccessfulResponse($contextResult);

        $this->assertIsArray($contextData['data']);
        $this->assertArrayHasKey('contextId', $contextData['data']);
        $this->assertIsString($contextData['data']['contextId']);

        self::$createdContextId = $contextData['data']['contextId'];

        $prompt = "Bonjour";
        $lmName = "GPTModel";

        $dto = new ConversationMakeDTO($prompt, $lmName, self::$createdContextId);

        $result = $this->chatbotClient->makeConversation($dto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('conversationId', $responseData['data']);
        $this->assertArrayHasKey('numberOfPairs', $responseData['data']);
        $this->assertArrayHasKey('botMessage', $responseData['data']);
        $this->assertNotEmpty($responseData['data']['conversationId']);

        $this->assertIsString($responseData['data']['conversationId']);
        $this->assertStringStartsWith('con_', $responseData['data']['conversationId']);
        $this->assertIsInt($responseData['data']['numberOfPairs']);
        $this->assertIsString($responseData['data']['botMessage']);

        self::$createdConversationId = $responseData['data']['conversationId'];
    }

    public function testContinueConversation(): void
    {
        if (self::$createdConversationId === null) {
            $this->markTestSkipped('No conversation created in previous test');
        }

        $conversationId = self::$createdConversationId;
        $prompt = "Comment allez-vous?";
        $lmName = "GPTModel";

        $dto = new ConversationContinueDTO($prompt, $conversationId, $lmName);

        $result = $this->chatbotClient->continueConversation($dto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('conversationId', $responseData['data']);
        $this->assertArrayHasKey('numberOfPairs', $responseData['data']);
        $this->assertArrayHasKey('botMessage', $responseData['data']);

        $this->assertIsString($responseData['data']['conversationId']);
        $this->assertIsInt($responseData['data']['numberOfPairs']);
        $this->assertIsString($responseData['data']['botMessage']);
    }

    public function testViewConversation(): void
    {
        if (self::$createdConversationId === null) {
            $this->markTestSkipped('No conversation created in previous test');
        }

        $conversationId = self::$createdConversationId;

        $dto = new ConversationViewDTO($conversationId);

        $result = $this->chatbotClient->viewConversation($dto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('contextId', $responseData['data']);
        $this->assertArrayHasKey('pairs', $responseData['data']);

        $this->assertIsString($responseData['data']['contextId']);
        $this->assertStringStartsWith('cot_', $responseData['data']['contextId']);
        $this->assertIsArray($responseData['data']['pairs']);
    }
}
