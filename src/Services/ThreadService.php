<?php

namespace ChatbotPhp\Services;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\DTO\Thread\ThreadCreateDTO;
use ChatbotPhp\DTO\Thread\ThreadContinueDTO;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ThreadService
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

    public function create(ThreadCreateDTO $dto): string
    {
        $response = $this->httpClient->request('POST', $this->apiUrls->createThread(), [
            'json' => [
                'ast_id' => $dto->astId,
                'message' => $dto->message
            ]
        ]);
        return $response->getContent();
    }

    public function continue(ThreadContinueDTO $dto): string
    {
        $response = $this->httpClient->request('POST', $this->apiUrls->continueThread(), [
            'json' => [
                'conversation_id' => $dto->conversationId,
                'message' => $dto->message
            ]
        ]);
        return $response->getContent();
    }
}
