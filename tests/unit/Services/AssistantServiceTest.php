<?php

namespace ChatbotPhp\Tests\Services;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Assistant\AssistantAttachFileDTO;
use ChatbotPhp\DTO\Assistant\AssistantViewDTO;
use ChatbotPhp\Services\AssistantService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AssistantServiceTest extends TestCase
{
    public function testCreateAssistantWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": {"assistantId": "ast_68f638a37c2474.79919725", ' .
                '"externalAssistantId": "asst_XaMT6QXvuP7AUJfZaeXql0wN"}}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new AssistantService($mockHttpClient, new ApiUrls());
        $dto = new AssistantCreateDTO('cot_68e7aeb0a1f43', ['file-KNpZsP3NBAajzDETBqZQpX']);

        $result = $service->create($dto);

        $this->assertEquals('{"success": true, "data": {"assistantId": "ast_68f638a37c2474.79919725", ' .
            '"externalAssistantId": "asst_XaMT6QXvuP7AUJfZaeXql0wN"}}', $result);
    }

    public function testCreateAssistantWithEmptyFileIds(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": {"assistantId": "ast_123", ' .
                '"externalAssistantId": "asst_xyz"}}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new AssistantService($mockHttpClient, new ApiUrls());
        $dto = new AssistantCreateDTO('cot_68e7aeb0a1f43', []);

        $result = $service->create($dto);

        $this->assertEquals('{"success": true, "data": {"assistantId": "ast_123", ' .
            '"externalAssistantId": "asst_xyz"}}', $result);
    }

    public function testViewAssistantWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": {"assistantId": "ast_abc123", "name": "Mon Assistant IA", ' .
                '"instructions": "Tu es un assistant IA spécialisé dans l\'aide aux développeurs.", ' .
                '"externalAssistantId": "asst_xyz789", "fileIds": ["fil-abc123", "fil-def456"], ' .
                '"createdAt": "2024-01-15 10:30:00"}}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new AssistantService($mockHttpClient, new ApiUrls());
        $dto = new AssistantViewDTO('ast_abc123');

        $result = $service->view($dto);

        $this->assertEquals('{"success": true, "data": {"assistantId": "ast_abc123", "name": "Mon Assistant IA", ' .
            '"instructions": "Tu es un assistant IA spécialisé dans l\'aide aux développeurs.", ' .
            '"externalAssistantId": "asst_xyz789", "fileIds": ["fil-abc123", "fil-def456"], ' .
            '"createdAt": "2024-01-15 10:30:00"}}', $result);
    }

    public function testUpdateAssistantWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": {"assistantId": "ast_abc123", ' .
                '"fileIds": ["fil-abc123", "fil-def456"]}}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new AssistantService($mockHttpClient, new ApiUrls());
        $dto = new AssistantAttachFileDTO('ast_abc123', ['fil-abc123', 'fil-def456']);

        $result = $service->attachFiles($dto);

        $this->assertEquals('{"success": true, "data": {"assistantId": "ast_abc123", ' .
            '"fileIds": ["fil-abc123", "fil-def456"]}}', $result);
    }

    public function testUpdateAssistantWithEmptyFileIds(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": {"assistantId": "ast_abc123", "fileIds": []}}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new AssistantService($mockHttpClient, new ApiUrls());
        $dto = new AssistantAttachFileDTO('ast_abc123', []);

        $result = $service->attachFiles($dto);

        $this->assertEquals('{"success": true, "data": {"assistantId": "ast_abc123", "fileIds": []}}', $result);
    }

    public function testDeleteAssistantWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": {"assistantId": "ast_68f638a37c2474.79919725", ' .
                '"externalAssistantId": "asst_XaMT6QXvuP7AUJfZaeXql0wN"}}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new AssistantService($mockHttpClient, new ApiUrls());
        $dto = new AssistantDeleteDTO('ast_68f638a37c2474.79919725');

        $result = $service->delete($dto);

        $this->assertEquals('{"success": true, "data": {"assistantId": "ast_68f638a37c2474.79919725", ' .
            '"externalAssistantId": "asst_XaMT6QXvuP7AUJfZaeXql0wN"}}', $result);
    }
}
