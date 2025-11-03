<?php

namespace ChatbotPhp;

use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Assistant\AssistantUpdateDTO;
use ChatbotPhp\DTO\Assistant\AssistantViewDTO;
use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use ChatbotPhp\DTO\Context\ContextUpdateDTO;
use ChatbotPhp\DTO\Conversation\ConversationMakeDTO;
use ChatbotPhp\DTO\Conversation\ConversationContinueDTO;
use ChatbotPhp\DTO\Conversation\ConversationViewDTO;
use ChatbotPhp\DTO\File\FileUploadDTO;
use ChatbotPhp\DTO\File\FileViewDTO;
use ChatbotPhp\DTO\File\FileDeleteDTO;
use ChatbotPhp\DTO\Thread\ThreadCreateDTO;
use ChatbotPhp\DTO\Thread\ThreadContinueDTO;
use ChatbotPhp\Resources\Assistants;
use ChatbotPhp\Resources\Contexts;
use ChatbotPhp\Resources\Conversations;
use ChatbotPhp\Resources\Files;
use ChatbotPhp\Resources\Threads;
use ChatbotPhp\Services\AssistantService;
use ChatbotPhp\Services\ContextService;
use ChatbotPhp\Services\ConversationService;
use ChatbotPhp\Services\FileService;
use ChatbotPhp\Services\ThreadService;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatbotClient implements ChatbotClientInterface
{
    private AssistantService $assistantService;
    private ContextService $contextService;
    private ConversationService $conversationService;
    private FileService $fileService;
    private ThreadService $threadService;
    private HttpClientInterface $httpClient;
    private ApiUrls $apiUrls;

    public function __construct(
        ?HttpClientInterface $httpClient = null,
        ?ApiUrls $apiUrls = null
    ) {
        $this->httpClient = $httpClient ?? new CurlHttpClient();
        $this->apiUrls = $apiUrls ?? new ApiUrls();
        $this->assistantService = new AssistantService($this->httpClient, $this->apiUrls);
        $this->contextService = new ContextService($this->httpClient, $this->apiUrls);
        $this->conversationService = new ConversationService($this->httpClient, $this->apiUrls);
        $this->fileService = new FileService($this->httpClient, $this->apiUrls);
        $this->threadService = new ThreadService($this->httpClient, $this->apiUrls);
    }

    public function createContext(ContextCreateDTO $dto): string
    {
        return $this->contextService->create($dto);
    }

    public function viewContext(string $contextId): string
    {
        return $this->contextService->view($contextId);
    }

    public function updateContext(ContextUpdateDTO $dto): string
    {
        return $this->contextService->update($dto);
    }

    public function deleteContext(ContextDeleteDTO $dto): string
    {
        return $this->contextService->delete($dto);
    }

    public function uploadFile(FileUploadDTO $dto): string
    {
        return $this->fileService->upload($dto);
    }

    public function listFiles(): string
    {
        return $this->fileService->list();
    }

    public function viewFile(FileViewDTO $dto): string
    {
        return $this->fileService->view($dto);
    }

    public function deleteFile(FileDeleteDTO $dto): string
    {
        return $this->fileService->delete($dto);
    }

    public function createAssistant(AssistantCreateDTO $dto): string
    {
        return $this->assistantService->create($dto);
    }

    public function viewAssistant(AssistantViewDTO $dto): string
    {
        return $this->assistantService->view($dto);
    }

    public function updateAssistant(AssistantUpdateDTO $dto): string
    {
        return $this->assistantService->update($dto);
    }

    public function deleteAssistant(AssistantDeleteDTO $dto): string
    {
        return $this->assistantService->delete($dto);
    }

    public function makeConversation(ConversationMakeDTO $dto): string
    {
        return $this->conversationService->make($dto);
    }

    public function continueConversation(ConversationContinueDTO $dto): string
    {
        return $this->conversationService->continue($dto);
    }

    public function viewConversation(ConversationViewDTO $dto): string
    {
        return $this->conversationService->view($dto);
    }

    public function createThread(ThreadCreateDTO $dto): string
    {
        return $this->threadService->create($dto);
    }

    public function continueThread(ThreadContinueDTO $dto): string
    {
        return $this->threadService->continue($dto);
    }

    public function assistants(): Assistants
    {
        return new Assistants($this->assistantService);
    }

    public function files(): Files
    {
        return new Files($this->fileService);
    }

    public function conversations(): Conversations
    {
        return new Conversations($this->conversationService);
    }

    public function contexts(): Contexts
    {
        return new Contexts($this->contextService);
    }

    public function threads(): Threads
    {
        return new Threads($this->threadService);
    }
}
