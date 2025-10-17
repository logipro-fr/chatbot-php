<?php

namespace ChatbotPhp;

use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use ChatbotPhp\DTO\Context\ContextUpdateDTO;

interface ChatbotClientInterface
{
    public function createContext(ContextCreateDTO $dto): string;
    public function viewContext(string $contextId): string;
    public function updateContext(ContextUpdateDTO $dto): string;
    public function deleteContext(ContextDeleteDTO $dto): string;
}
