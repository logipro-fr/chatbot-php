<?php

namespace Tests\Integration;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\ChatbotClient;
use PHPUnit\Framework\TestCase;

abstract class BaseIntegrationTestCase extends TestCase
{
    protected ChatbotClient $chatbotClient;
    protected string $baseUrl;

    protected function setUp(): void
    {
        parent::setUp();

        $envUrl = $_ENV['CHATBOT_API_URL'] ?? null;
        $this->baseUrl = is_string($envUrl) ? $envUrl : 'http://172.17.0.1:11080/api';
        $this->chatbotClient = new ChatbotClient(null, new ApiUrls($this->baseUrl));
    }

    protected function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function getChatbotClient(): ChatbotClient
    {
        return $this->chatbotClient;
    }

    /**
     * @return array{success: bool, data: mixed}
     */
    protected function assertStandardApiResponse(string $response): array
    {
        $this->assertJson($response);

        $responseData = json_decode($response, true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('data', $responseData);

        /** @var array{success: bool, data: mixed} $responseData */
        return $responseData;
    }

    /**
     * @return array{success: bool, data: mixed}
     */
    protected function assertSuccessfulResponse(string $response): array
    {
        $responseData = $this->assertStandardApiResponse($response);
        $this->assertTrue($responseData['success']);

        return $responseData;
    }
}
