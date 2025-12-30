<?php

namespace Tests\Integration;

use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;

class AssistantCreationDeleteIntegrationTest extends BaseIntegrationTestCase
{
    public function testCreateAndDeleteAssistant(): void
    {
        //Arrange
        $dtoContextCreate = new ContextCreateDTO('Integration Test - Assistant Creation and Delete');
        $response = $this->chatbotClient->createContext($dtoContextCreate);
        /** @var array{success: bool, data: array{contextId: string}} $dataContext */
        $dataContext = json_decode($response, true);
        $contextId = $dataContext['data']['contextId'];
        $dto = new AssistantCreateDTO($contextId);

        //Act
        $result = $this->chatbotClient->createAssistant($dto);
        /** @var array{success: bool, data: array{assistantId: string}} $dataAssistant */
        $dataAssistant = json_decode($result, true);

        //Assert
        $this->assertArrayHasKey('assistantId', $dataAssistant['data']);
        $this->assertStringStartsWith('ast_', $dataAssistant['data']['assistantId']);

        $assistantId = $dataAssistant['data']['assistantId'];
        $dto = new AssistantDeleteDTO($assistantId);

        $resultDelete = $this->chatbotClient->deleteAssistant($dto);
        /** @var array{success: bool, data: array{externalAssistantId: string}} $dataDelete */
        $dataDelete = json_decode($resultDelete, true);

        $this->assertTrue($dataDelete['success']);
        $this->assertStringStartsWith('asst_', $dataDelete['data']['externalAssistantId']);

        $dtoContextDelete = new ContextDeleteDTO($contextId);

        $this->chatbotClient->deleteContext($dtoContextDelete);
    }
}
