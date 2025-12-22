<?php

namespace ChatbotPhp\Tests\Services;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\DTO\Conversation\ConversationMakeDTO;
use ChatbotPhp\DTO\Conversation\ConversationContinueDTO;
use ChatbotPhp\DTO\Conversation\ConversationViewDTO;
use ChatbotPhp\Services\ConversationService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ConversationServiceTest extends TestCase
{
    public function testMakeConversationWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $expectedResponse = '{"success": true, "data": {"conversationId": "con_66c58c7ee6c58", ' .
            '"numberOfPairs": 1, "botMessage": "Bonjour! Comment puis-je vous aider aujourd\'hui?"}}';
        $mockResponse
            ->method('getContent')
            ->willReturn($expectedResponse);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new ConversationService($mockHttpClient, new ApiUrls());
        $dto = new ConversationMakeDTO('Bonjour', 'GPTModel', 'cot_66b46fefe29d5');

        $result = $service->make($dto);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testContinueConversationWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $expectedResponse = '{"success": true, "data": {"conversationId": "con_66c58c7ee6c58", ' .
            '"numberOfPairs": 2, "botMessage": "Je suis là pour vous aider."}, "description": "string"}';
        $mockResponse
            ->method('getContent')
            ->willReturn($expectedResponse);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new ConversationService($mockHttpClient, new ApiUrls());
        $dto = new ConversationContinueDTO('Merci', 'con_66680a4a5ee25', 'GPTModel');

        $result = $service->continue($dto);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testViewConversationWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $expectedResponse = '{"success": true, "data": {"contextId": "cot_68f882ea112c8", "pairs": [[], []]}}';
        $mockResponse
            ->method('getContent')
            ->willReturn($expectedResponse);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new ConversationService($mockHttpClient, new ApiUrls());
        $dto = new ConversationViewDTO('con_66cd76e11574');

        $result = $service->view($dto);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testListConversationsWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $expectedResponse = '{"success": true, "data":
        {"conversations": [{"conversationId": "con_123", "title": "Conversation du 10/11/2025 13:40"}]}}';
        $mockResponse
            ->method('getContent')
            ->willReturn($expectedResponse);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new ConversationService($mockHttpClient, new ApiUrls());

        $result = $service->list('ast_123456');

        $this->assertEquals($expectedResponse, $result);
    }
}
