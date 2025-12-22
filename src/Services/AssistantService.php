<?php

namespace ChatbotPhp\Services;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Assistant\AssistantAttachFileDTO;
use ChatbotPhp\DTO\Assistant\AssistantViewDTO;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AssistantService
{
    private HttpClientInterface $httpClient;
    private ApiUrls $apiUrls;

    public function __construct(
        HttpClientInterface $httpClient,
        ApiUrls $apiUrls
    ) {
        $this->httpClient = $httpClient;
        $this->apiUrls = $apiUrls;
    }

    public function create(AssistantCreateDTO $dto): string
    {
        $response = $this->httpClient->request('POST', $this->apiUrls->createAssistant(), [
            'json' => [
                'context_id' => $dto->contextId,
                'file_ids' => $dto->fileIds
            ]
        ]);
        return $response->getContent();
    }

    public function view(AssistantViewDTO $dto): string
    {
        $response = $this->httpClient->request('GET', $this->apiUrls->viewAssistant($dto->assistantId));
        return $response->getContent();
    }

    public function attachFiles(AssistantAttachFileDTO $dto): string
    {
        $response = $this->httpClient->request('PUT', $this->apiUrls->attachAssistantFiles($dto->assistantId), [
            'json' => [
                'file_ids' => $dto->fileIds
            ]
        ]);
        return $response->getContent();
    }

    public function delete(AssistantDeleteDTO $dto): string
    {
        $response = $this->httpClient->request('DELETE', $this->apiUrls->deleteAssistant($dto->assistantId));
        return $response->getContent();
    }
}
