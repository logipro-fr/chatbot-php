<?php

namespace ChatbotPhp;

use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Assistant\AssistantUpdateDTO;
use ChatbotPhp\DTO\Assistant\AssistantViewDTO;
use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use ChatbotPhp\DTO\Context\ContextUpdateDTO;
use ChatbotPhp\DTO\File\FileUploadDTO;
use ChatbotPhp\DTO\File\FileViewDTO;
use ChatbotPhp\DTO\File\FileDeleteDTO;
use ChatbotPhp\Services\AssistantService;
use ChatbotPhp\Services\ContextService;
use ChatbotPhp\Services\FileService;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatbotClient implements ChatbotClientInterface
{
    private AssistantService $assistantService;
    private ContextService $contextService;
    private FileService $fileService;
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
        $this->fileService = new FileService($this->httpClient, $this->apiUrls);
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
}
