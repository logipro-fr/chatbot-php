<?php

namespace ChatbotPhp\Resources;

use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use ChatbotPhp\DTO\Context\ContextUpdateDTO;
use ChatbotPhp\Services\ContextService;

class Contexts
{
    private ContextService $contextService;

    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function create(array $parameters): string
    {
        /** @var string $contextMessage */
        $contextMessage = $parameters['context_message'];
        $dto = new ContextCreateDTO($contextMessage);

        return $this->contextService->create($dto);
    }

    public function retrieve(string $contextId): string
    {
        return $this->contextService->view($contextId);
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function update(array $parameters): string
    {
        /** @var string $id */
        $id = $parameters['id'];
        /** @var string $newMessage */
        $newMessage = $parameters['new_message'];
        $dto = new ContextUpdateDTO($id, $newMessage);

        return $this->contextService->update($dto);
    }

    public function delete(string $contextId): string
    {
        $dto = new ContextDeleteDTO($contextId);

        return $this->contextService->delete($dto);
    }
}
