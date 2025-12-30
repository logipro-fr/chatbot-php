<?php

namespace Tests\Integration;

use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Assistant\AssistantAttachFileDTO;
use ChatbotPhp\DTO\Assistant\AssistantDetachFileDTO;
use ChatbotPhp\DTO\Assistant\AssistantViewDTO;
use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use Exception;

class AssistantIntegrationTest extends BaseIntegrationTestCase
{
    private string $createdAssistantId = '';
    private string $contextId = '';

    protected function setUp(): void
    {
        parent::setUp();

        //Context
        $dtoContextCreate = new ContextCreateDTO('Integration Test - Assistant Creation and Delete');
        $response = $this->chatbotClient->createContext($dtoContextCreate);
        /** @var array{success: bool, data: array{contextId: string}} $dataContext */
        $dataContext = json_decode($response, true);
        $this->contextId = $dataContext['data']['contextId'];

        //Assistant
        $dto = new AssistantCreateDTO($this->contextId);
            $result = $this->chatbotClient->createAssistant($dto);
        /** @var array{success: bool, data: array{assistantId: string}} $dataAssistant */
        $dataAssistant = json_decode($result, true);
        $this->createdAssistantId = $dataAssistant['data']['assistantId'];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        //Delete Assistant
        $dto = new AssistantDeleteDTO($this->createdAssistantId);
        $this->chatbotClient->deleteAssistant($dto);
        $this->createdAssistantId = '';

        //Delete Context
        $dtoContextDelete = new ContextDeleteDTO($this->contextId);
        $this->chatbotClient->deleteContext($dtoContextDelete);
        $this->contextId = '';
    }


    public function testViewAssistant(): void
    {
        $assistantId = $this->createdAssistantId;

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

    public function testAttachAssistantFiles(): void
    {
        $assistantId = $this->createdAssistantId;
        $fileIds = ['file-LcwTe45pSDCd1tCUqgw2kJ'];
        $dto = new AssistantAttachFileDTO($assistantId, $fileIds);

        $result = $this->chatbotClient->attachAssistantFiles($dto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('assistantId', $responseData['data']);

        if (isset($responseData['data']['fileIds'])) {
            $this->assertIsArray($responseData['data']['fileIds']);
        }
    }

    public function testDetachAssistantFiles(): void
    {
        $assistantId = $this->createdAssistantId;
        $fileIds = ['file-LcwTe45pSDCd1tCUqgw2kJ'];
        $fileId = 'file-LcwTe45pSDCd1tCUqgw2kJ';

        $dtoAttach = new AssistantAttachFileDTO($assistantId, $fileIds);

        $this->chatbotClient->attachAssistantFiles($dtoAttach);
        $dto = new AssistantDetachFileDTO($assistantId, $fileId);

        $result = $this->chatbotClient->detachAssistantFiles($dto);

        $this->assertSuccessfulResponse($result);
    }
}
