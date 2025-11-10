<?php

namespace ChatbotPhp\Tests\Resources;

use ChatbotPhp\DTO\Conversation\ConversationContinueDTO;
use ChatbotPhp\DTO\Conversation\ConversationMakeDTO;
use ChatbotPhp\DTO\Conversation\ConversationViewDTO;
use ChatbotPhp\Resources\Conversations;
use ChatbotPhp\Services\ConversationService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConversationsTest extends TestCase
{
    private Conversations $conversations;
    /** @var ConversationService&MockObject */
    private $conversationService;

    protected function setUp(): void
    {
        $this->conversationService = $this->createMock(ConversationService::class);
        $this->conversations = new Conversations($this->conversationService);
    }

    public function testCreate(): void
    {
        $prompt = 'Hello';
        $lmName = 'gpt-4';
        $context = 'cot_123';
        $expectedResponse = '{"success":true,"data":{"conversationId":"con_123"}}';

        $this->conversationService
            ->expects($this->once())
            ->method('make')
            ->with($this->callback(function (ConversationMakeDTO $dto) use ($prompt, $lmName, $context) {
                return $dto->prompt === $prompt && $dto->lmName === $lmName && $dto->context === $context;
            }))
            ->willReturn($expectedResponse);

        $result = $this->conversations->create([
            'prompt' => $prompt,
            'lm_name' => $lmName,
            'context' => $context
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testContinue(): void
    {
        $prompt = 'Continue';
        $convId = 'con_123';
        $lmName = 'gpt-4';
        $expectedResponse = '{"success":true,"data":{"conversationId":"con_123"}}';

        $this->conversationService
            ->expects($this->once())
            ->method('continue')
            ->with($this->callback(function (ConversationContinueDTO $dto) use ($prompt, $convId, $lmName) {
                return $dto->prompt === $prompt && $dto->convId === $convId && $dto->lmName === $lmName;
            }))
            ->willReturn($expectedResponse);

        $result = $this->conversations->continue([
            'prompt' => $prompt,
            'conv_id' => $convId,
            'lm_name' => $lmName
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testRetrieve(): void
    {
        $conversationId = 'con_123';
        $expectedResponse = '{"success":true,"data":{"conversationId":"con_123"}}';

        $this->conversationService
            ->expects($this->once())
            ->method('view')
            ->with($this->callback(function (ConversationViewDTO $dto) use ($conversationId) {
                return $dto->id === $conversationId;
            }))
            ->willReturn($expectedResponse);

        $result = $this->conversations->retrieve($conversationId);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testList(): void
    {
        $assistantId = 'ast_123';
        $expectedResponse = '{"success":true,"data":{"conversations":[{"conversationId":"con_123","title":"Conversation du 10/11/2025 13:40"}]}}';

        $this->conversationService
            ->expects($this->once())
            ->method('list')
            ->with($assistantId)
            ->willReturn($expectedResponse);

        $result = $this->conversations->list($assistantId);

        $this->assertEquals($expectedResponse, $result);
    }
}
