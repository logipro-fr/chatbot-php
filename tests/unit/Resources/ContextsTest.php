<?php

namespace ChatbotPhp\Tests\Resources;

use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use ChatbotPhp\DTO\Context\ContextUpdateDTO;
use ChatbotPhp\Resources\Contexts;
use ChatbotPhp\Services\ContextService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContextsTest extends TestCase
{
    private Contexts $contexts;
    /** @var ContextService&MockObject */
    private $contextService;

    protected function setUp(): void
    {
        $this->contextService = $this->createMock(ContextService::class);
        $this->contexts = new Contexts($this->contextService);
    }

    public function testCreate(): void
    {
        $contextMessage = 'Test context';
        $expectedResponse = '{"success":true,"data":{"contextId":"cot_123"}}';

        $this->contextService
            ->expects($this->once())
            ->method('create')
            ->with($this->callback(function (ContextCreateDTO $dto) use ($contextMessage) {
                return $dto->contextMessage === $contextMessage;
            }))
            ->willReturn($expectedResponse);

        $result = $this->contexts->create([
            'context_message' => $contextMessage
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testRetrieve(): void
    {
        $contextId = 'cot_123';
        $expectedResponse = '{"success":true,"data":{"contextId":"cot_123"}}';

        $this->contextService
            ->expects($this->once())
            ->method('view')
            ->with($contextId)
            ->willReturn($expectedResponse);

        $result = $this->contexts->retrieve($contextId);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testUpdate(): void
    {
        $id = 'cot_123';
        $newMessage = 'Updated message';
        $expectedResponse = '{"success":true,"data":{"contextId":"cot_123"}}';

        $this->contextService
            ->expects($this->once())
            ->method('update')
            ->with($this->callback(function (ContextUpdateDTO $dto) use ($id, $newMessage) {
                return $dto->id === $id && $dto->newMessage === $newMessage;
            }))
            ->willReturn($expectedResponse);

        $result = $this->contexts->update([
            'id' => $id,
            'new_message' => $newMessage
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testDelete(): void
    {
        $contextId = 'cot_123';
        $expectedResponse = '{"success":true,"data":{"message":"deleted"}}';

        $this->contextService
            ->expects($this->once())
            ->method('delete')
            ->with($this->callback(function (ContextDeleteDTO $dto) use ($contextId) {
                return $dto->id === $contextId;
            }))
            ->willReturn($expectedResponse);

        $result = $this->contexts->delete($contextId);

        $this->assertEquals($expectedResponse, $result);
    }
}
