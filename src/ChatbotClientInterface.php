<?php

namespace ChatbotPhp;

use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Assistant\AssistantUpdateDTO;
use ChatbotPhp\DTO\Assistant\AssistantViewDTO;
use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use ChatbotPhp\DTO\Context\ContextUpdateDTO;
use ChatbotPhp\DTO\File\FileDeleteDTO;
use ChatbotPhp\DTO\File\FileUploadDTO;
use ChatbotPhp\DTO\File\FileViewDTO;

interface ChatbotClientInterface
{
    public function createContext(ContextCreateDTO $dto): string;
    public function viewContext(string $contextId): string;
    public function updateContext(ContextUpdateDTO $dto): string;
    public function deleteContext(ContextDeleteDTO $dto): string;

    public function uploadFile(FileUploadDTO $dto): string;
    public function listFiles(): string;
    public function viewFile(FileViewDTO $dto): string;
    public function deleteFile(FileDeleteDTO $dto): string;

    public function createAssistant(AssistantCreateDTO $dto): string;
    public function viewAssistant(AssistantViewDTO $dto): string;
    public function updateAssistant(AssistantUpdateDTO $dto): string;
    public function deleteAssistant(AssistantDeleteDTO $dto): string;
}
