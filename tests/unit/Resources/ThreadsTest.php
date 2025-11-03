<?php

namespace ChatbotPhp\Tests\Resources;

use ChatbotPhp\DTO\Thread\ThreadContinueDTO;
use ChatbotPhp\DTO\Thread\ThreadCreateDTO;
use ChatbotPhp\Resources\Threads;
use ChatbotPhp\Services\ThreadService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ThreadsTest extends TestCase
{
    private Threads $threads;
    /** @var ThreadService&MockObject */
    private $threadService;

    protected function setUp(): void
    {
        $this->threadService = $this->createMock(ThreadService::class);
        $this->threads = new Threads($this->threadService);
    }

    public function testCreate(): void
    {
        $astId = 'ast_123';
        $message = 'Hello';
        $expectedResponse = '{"success":true,"data":{"conversationId":"con_123"}}';

        $this->threadService
            ->expects($this->once())
            ->method('create')
            ->with($this->callback(function (ThreadCreateDTO $dto) use ($astId, $message) {
                return $dto->astId === $astId && $dto->message === $message;
            }))
            ->willReturn($expectedResponse);

        $result = $this->threads->create([
            'ast_id' => $astId,
            'message' => $message
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testContinue(): void
    {
        $conversationId = 'con_123';
        $message = 'Continue';
        $expectedResponse = '{"success":true,"data":{"conversationId":"con_123"}}';

        $this->threadService
            ->expects($this->once())
            ->method('continue')
            ->with($this->callback(function (ThreadContinueDTO $dto) use ($conversationId, $message) {
                return $dto->conversationId === $conversationId && $dto->message === $message;
            }))
            ->willReturn($expectedResponse);

        $result = $this->threads->continue([
            'conversation_id' => $conversationId,
            'message' => $message
        ]);

        $this->assertEquals($expectedResponse, $result);
    }
}
