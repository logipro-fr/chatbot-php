<?php

namespace ChatbotPhp;

use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Assistant\AssistantAttachFileDTO;
use ChatbotPhp\DTO\Assistant\AssistantDetachFileDTO;
use ChatbotPhp\DTO\Assistant\AssistantViewDTO;
use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use ChatbotPhp\DTO\Context\ContextUpdateDTO;
use ChatbotPhp\DTO\Conversation\ConversationMakeDTO;
use ChatbotPhp\DTO\Conversation\ConversationContinueDTO;
use ChatbotPhp\DTO\Conversation\ConversationViewDTO;
use ChatbotPhp\DTO\File\FileDeleteDTO;
use ChatbotPhp\DTO\File\FileUploadDTO;
use ChatbotPhp\DTO\File\FileViewDTO;
use ChatbotPhp\DTO\Thread\ThreadCreateDTO;
use ChatbotPhp\DTO\Thread\ThreadContinueDTO;
use ChatbotPhp\Resources\Assistants;
use ChatbotPhp\Resources\Contexts;
use ChatbotPhp\Resources\Conversations;
use ChatbotPhp\Resources\Files;
use ChatbotPhp\Resources\Threads;

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
    public function attachAssistantFiles(AssistantAttachFileDTO $dto): string;
    public function detachAssistantFiles(AssistantDetachFileDTO $dot): string;
    public function deleteAssistant(AssistantDeleteDTO $dto): string;

    public function makeConversation(ConversationMakeDTO $dto): string;
    public function continueConversation(ConversationContinueDTO $dto): string;
    public function viewConversation(ConversationViewDTO $dto): string;

    public function createThread(ThreadCreateDTO $dto): string;
    public function continueThread(ThreadContinueDTO $dto): string;

    public function assistants(): Assistants;
    public function files(): Files;
    public function conversations(): Conversations;
    public function contexts(): Contexts;
    public function threads(): Threads;
}
