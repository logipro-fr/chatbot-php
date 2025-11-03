<?php

namespace ChatbotPhp\Resources;

use ChatbotPhp\DTO\Thread\ThreadContinueDTO;
use ChatbotPhp\DTO\Thread\ThreadCreateDTO;
use ChatbotPhp\Services\ThreadService;

class Threads
{
    private ThreadService $threadService;

    public function __construct(ThreadService $threadService)
    {
        $this->threadService = $threadService;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function create(array $parameters): string
    {
        /** @var string $astId */
        $astId = $parameters['ast_id'];
        /** @var string $message */
        $message = $parameters['message'];
        $dto = new ThreadCreateDTO($astId, $message);

        return $this->threadService->create($dto);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function continue(array $parameters): string
    {
        /** @var string $conversationId */
        $conversationId = $parameters['conversation_id'];
        /** @var string $message */
        $message = $parameters['message'];
        $dto = new ThreadContinueDTO($conversationId, $message);

        return $this->threadService->continue($dto);
    }
}
