<?php

namespace ChatbotPhp\Tests;

use ChatbotPhp\ChatbotClient;
use ChatbotPhp\ChatbotClientFactory;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ChatbotClientFactoryTest extends TestCase
{
    public function testCreateMockChatbot(): void
    {
        $factory = new ChatbotClientFactory();
        $client = $factory->createMockChatbot();
        $this->assertInstanceOf(ChatbotClient::class, $client);
    }

    public function testCreateMockChatbotThrowsBadRequestException(): void
    {
        $factory = new ChatbotClientFactory();

        $reflection = new ReflectionClass($factory);
        $method = $reflection->getMethod('callableResponse');
        $method->setAccessible(true);

        $this->expectException(BadRequestException::class);

        $method->invoke($factory, 'POST', '/v1/badRoutes', []);
    }

    public function testCallableResponseThrowsOnNonPostMethod(): void
    {
        $factory = new ChatbotClientFactory();

        $reflection = new \ReflectionClass($factory);
        $method = $reflection->getMethod('callableResponse');
        $method->setAccessible(true);

        $this->expectException(BadRequestException::class);

        $method->invoke($factory, 'GET', '/api/external/v1/context/make', []);
    }
}
