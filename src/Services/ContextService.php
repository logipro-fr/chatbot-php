<?php

namespace ChatbotPhp\Services;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use ChatbotPhp\DTO\Context\ContextUpdateDTO;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ContextService
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

    public function create(ContextCreateDTO $dto): string
    {
            $response = $this->httpClient->request('POST', $this->apiUrls->createContext(), [
                'json' => [
                    'ContextMessage' => $dto->contextMessage
                ]
            ]);
            return $response->getContent();
    }

    public function view(string $contextId): string
    {
        $response = $this->httpClient->request('GET', $this->apiUrls->viewContext($contextId));
        return $response->getContent();
    }

    public function update(ContextUpdateDTO $dto): string
    {
        $response = $this->httpClient->request('PATCH', $this->apiUrls->updateContext(), [
            'json' => [
                'Id' => $dto->id,
                'NewMessage' => $dto->newMessage
            ]
        ]);
        return $response->getContent();
    }

    public function delete(ContextDeleteDTO $dto): string
    {
        $response = $this->httpClient->request('DELETE', $this->apiUrls->deleteContext(), [
            'json' => [
                'Id' => $dto->id
            ]
        ]);
        return $response->getContent();
    }
}
