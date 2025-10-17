<?php

namespace ChatbotPhp;

use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use ChatbotPhp\DTO\Context\ContextUpdateDTO;
use ChatbotPhp\Services\ContextService;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatbotClient implements ChatbotClientInterface
{
    private ContextService $contextService;
    private HttpClientInterface $httpClient;
    private ApiUrls $apiUrls;

    public function __construct(
        ?HttpClientInterface $httpClient = null,
        ?ApiUrls $apiUrls = null
    ) {
        $this->httpClient = $httpClient ?? new CurlHttpClient();
        $this->apiUrls = $apiUrls ?? new ApiUrls();
        $this->contextService = new ContextService($this->httpClient, $this->apiUrls);
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
}
