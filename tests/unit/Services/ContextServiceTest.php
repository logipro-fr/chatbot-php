<?php

namespace ChatbotPhp\Tests\Services;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\Services\ContextService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ContextCreateServiceTest extends TestCase
{
    public function testCreateContextWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": {"contextId": "con_123"}}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new ContextService($mockHttpClient, new ApiUrls());
        $dto = new ContextCreateDTO('Test message');

        $result = $service->create($dto);

        $this->assertEquals('{"success": true, "data": {"contextId": "con_123"}}', $result);
    }

    public function testCreateContextWithLongMessage(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": {"contextId": "con_123"}}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new ContextService($mockHttpClient, new ApiUrls());
        $dto = new ContextCreateDTO(str_repeat('a', 1000));

        $result = $service->create($dto);

        $this->assertEquals('{"success": true, "data": {"contextId": "con_123"}}', $result);
    }
}
