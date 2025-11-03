<?php

namespace ChatbotPhp\Tests\Services;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\DTO\Thread\ThreadCreateDTO;
use ChatbotPhp\DTO\Thread\ThreadContinueDTO;
use ChatbotPhp\Services\ThreadService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ThreadServiceTest extends TestCase
{
    public function testCreateThreadWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $expectedResponse = '{"success": true, "data": {"conversationId": "con_abc123", ' .
            '"assistantMessage": "Bien sûr ! Je peux vous aider à documenter votre fonction PHP."}}';
        $mockResponse
            ->method('getContent')
            ->willReturn($expectedResponse);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new ThreadService($mockHttpClient, new ApiUrls());
        $dto = new ThreadCreateDTO('ast_abc123', 'Peux-tu m\'aider à documenter cette fonction PHP ?');

        $result = $service->create($dto);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testContinueThreadWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $expectedResponse = '{"success": true, "data": {"conversationId": "con_66c58c7ee6c58", ' .
            '"message": "Bien sûr ! Je vais vous expliquer plus en détail..."}, "description": "string"}';
        $mockResponse
            ->method('getContent')
            ->willReturn($expectedResponse);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new ThreadService($mockHttpClient, new ApiUrls());
        $dto = new ThreadContinueDTO('con_66680a4a5ee25', 'Peux-tu m\'expliquer plus en détail ?');

        $result = $service->continue($dto);

        $this->assertEquals($expectedResponse, $result);
    }
}
