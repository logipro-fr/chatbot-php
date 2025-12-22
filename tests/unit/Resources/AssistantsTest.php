<?php

namespace ChatbotPhp\Tests\Resources;

use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Assistant\AssistantAttachFileDTO;
use ChatbotPhp\DTO\Assistant\AssistantViewDTO;
use ChatbotPhp\Resources\Assistants;
use ChatbotPhp\Services\AssistantService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AssistantsTest extends TestCase
{
    private Assistants $assistants;
    /** @var AssistantService&MockObject */
    private $assistantService;

    protected function setUp(): void
    {
        $this->assistantService = $this->createMock(AssistantService::class);
        $this->assistants = new Assistants($this->assistantService);
    }

    public function testCreate(): void
    {
        $contextId = 'cot_123';
        $fileIds = ['file-abc', 'file-def'];
        $expectedResponse = '{"success":true,"data":{"assistantId":"ast_123"}}';

        $this->assistantService
            ->expects($this->once())
            ->method('create')
            ->with($this->callback(function (AssistantCreateDTO $dto) use ($contextId, $fileIds) {
                return $dto->contextId === $contextId && $dto->fileIds === $fileIds;
            }))
            ->willReturn($expectedResponse);

        $result = $this->assistants->create([
            'context_id' => $contextId,
            'file_ids' => $fileIds
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testCreateWithEmptyFileIds(): void
    {
        $contextId = 'cot_123';
        $expectedResponse = '{"success":true,"data":{"assistantId":"ast_123"}}';

        $this->assistantService
            ->expects($this->once())
            ->method('create')
            ->with($this->callback(function (AssistantCreateDTO $dto) use ($contextId) {
                return $dto->contextId === $contextId && $dto->fileIds === [];
            }))
            ->willReturn($expectedResponse);

        $result = $this->assistants->create([
            'context_id' => $contextId
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testRetrieve(): void
    {
        $assistantId = 'ast_123';
        $expectedResponse = '{"success":true,"data":{"assistantId":"ast_123"}}';

        $this->assistantService
            ->expects($this->once())
            ->method('view')
            ->with($this->callback(function (AssistantViewDTO $dto) use ($assistantId) {
                return $dto->assistantId === $assistantId;
            }))
            ->willReturn($expectedResponse);

        $result = $this->assistants->retrieve($assistantId);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testAttachFiles(): void
    {
        $assistantId = 'ast_123';
        $fileIds = ['file-abc', 'file-def'];
        $expectedResponse = '{"success":true,"data":{"assistantId":"ast_123"}}';

        $this->assistantService
            ->expects($this->once())
            ->method('attachFiles')
            ->with($this->callback(function (AssistantAttachFileDTO $dto) use ($assistantId, $fileIds) {
                return $dto->assistantId === $assistantId && $dto->fileIds === $fileIds;
            }))
            ->willReturn($expectedResponse);

        $result = $this->assistants->attachFiles([
            'assistant_id' => $assistantId,
            'file_ids' => $fileIds
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testDelete(): void
    {
        $assistantId = 'ast_123';
        $expectedResponse = '{"success":true,"data":{"assistantId":"ast_123"}}';

        $this->assistantService
            ->expects($this->once())
            ->method('delete')
            ->with($this->callback(function (AssistantDeleteDTO $dto) use ($assistantId) {
                return $dto->assistantId === $assistantId;
            }))
            ->willReturn($expectedResponse);

        $result = $this->assistants->delete($assistantId);

        $this->assertEquals($expectedResponse, $result);
    }
}
