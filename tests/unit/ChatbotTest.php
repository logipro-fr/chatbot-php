<?php

namespace ChatbotPhp\Tests;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\Chatbot;
use ChatbotPhp\ChatbotClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatbotTest extends TestCase
{
    public function testClientReturnsChatbotClientInstance(): void
    {
        $client = Chatbot::client();

        $this->assertInstanceOf(ChatbotClient::class, $client);
    }

    public function testClientWithBaseUrl(): void
    {
        $baseUrl = 'http://api.example.com';
        $client = Chatbot::client($baseUrl);

        $this->assertInstanceOf(ChatbotClient::class, $client);

        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('apiUrls');
        $property->setAccessible(true);
        $apiUrls = $property->getValue($client);

        $this->assertInstanceOf(ApiUrls::class, $apiUrls);
        $this->assertEquals($baseUrl, $apiUrls->getBaseUrl());
    }

    public function testClientWithoutBaseUrlUsesDefault(): void
    {
        $client = Chatbot::client();

        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('apiUrls');
        $property->setAccessible(true);
        $apiUrls = $property->getValue($client);

        $this->assertInstanceOf(ApiUrls::class, $apiUrls);
        $this->assertEquals(ApiUrls::BASE_URL_PROD, $apiUrls->getBaseUrl());
    }

    public function testClientHasHttpClient(): void
    {
        $client = Chatbot::client();

        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $httpClient = $property->getValue($client);

        $this->assertInstanceOf(HttpClientInterface::class, $httpClient);
        $this->assertInstanceOf(CurlHttpClient::class, $httpClient);
    }
}
