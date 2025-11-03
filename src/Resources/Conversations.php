<?php

namespace ChatbotPhp\Resources;

use ChatbotPhp\DTO\Conversation\ConversationContinueDTO;
use ChatbotPhp\DTO\Conversation\ConversationMakeDTO;
use ChatbotPhp\DTO\Conversation\ConversationViewDTO;
use ChatbotPhp\Services\ConversationService;

class Conversations
{
    private ConversationService $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function create(array $parameters): string
    {
        /** @var string $prompt */
        $prompt = $parameters['prompt'];
        /** @var string $lmName */
        $lmName = $parameters['lm_name'];
        /** @var string $context */
        $context = $parameters['context'];
        $dto = new ConversationMakeDTO($prompt, $lmName, $context);

        return $this->conversationService->make($dto);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function continue(array $parameters): string
    {
        /** @var string $prompt */
        $prompt = $parameters['prompt'];
        /** @var string $convId */
        $convId = $parameters['conv_id'];
        /** @var string $lmName */
        $lmName = $parameters['lm_name'];
        $dto = new ConversationContinueDTO($prompt, $convId, $lmName);

        return $this->conversationService->continue($dto);
    }

    public function retrieve(string $conversationId): string
    {
        $dto = new ConversationViewDTO($conversationId);

        return $this->conversationService->view($dto);
    }
}
