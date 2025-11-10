<?php

namespace ChatbotPhp\Services;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\DTO\Conversation\ConversationMakeDTO;
use ChatbotPhp\DTO\Conversation\ConversationContinueDTO;
use ChatbotPhp\DTO\Conversation\ConversationViewDTO;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ConversationService
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

    public function make(ConversationMakeDTO $dto): string
    {
        $response = $this->httpClient->request('POST', $this->apiUrls->makeConversation(), [
            'json' => [
                'Prompt' => $dto->prompt,
                'lmName' => $dto->lmName,
                'context' => $dto->context
            ]
        ]);
        return $response->getContent();
    }

    public function continue(ConversationContinueDTO $dto): string
    {
        $response = $this->httpClient->request('POST', $this->apiUrls->continueConversation(), [
            'json' => [
                'Prompt' => $dto->prompt,
                'convId' => $dto->convId,
                'lmName' => $dto->lmName
            ]
        ]);
        return $response->getContent();
    }

    public function view(ConversationViewDTO $dto): string
    {
        $response = $this->httpClient->request('GET', $this->apiUrls->viewConversation($dto->id));
        return $response->getContent();
    }

    public function list(string $assistantId): string
    {
        $response = $this->httpClient->request('GET', $this->apiUrls->listConversations($assistantId));
        return $response->getContent();
    }
}
