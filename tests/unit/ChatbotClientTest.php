<?php

namespace ChatbotPhp\Tests;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\ChatbotClient;
use ChatbotPhp\ChatbotClientFactory;
use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use ChatbotPhp\DTO\Context\ContextUpdateDTO;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\json_decode;

class ChatbotClientTest extends TestCase
{
    protected ChatbotClient $client;

    protected function setUp(): void
    {
        $chatbotClientFactory = new ChatbotClientFactory();
        $this->client = $chatbotClientFactory->createMockChatbot();
    }

    public function testCreateContext(): void
    {
        $contextMessage = 'Test context message';
        $dto = new ContextCreateDTO($contextMessage);

        $response = $this->client->createContext($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $data = $responseData['data'];
        $this->assertIsArray($data);
        /** @var string $contextId */
        $contextId = $data['contextId'];
        $this->assertStringStartsWith('con_', $contextId);
    }

    public function testCreateContextWithSpecialCharacters(): void
    {
        $contextMessage = 'Message with special chars: éàçù';
        $dto = new ContextCreateDTO($contextMessage);

        $response = $this->client->createContext($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $data = $responseData['data'];
        $this->assertIsArray($data);
        /** @var string $contextId */
        $contextId = $data['contextId'];
        $this->assertStringStartsWith('con_', $contextId);
    }

    public function testViewContext(): void
    {
        $contextId = 'cot_66b46fefe29d5';

        $response = $this->client->viewContext($contextId);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['contextMessage']));
        $this->assertEquals('I\'m a context', $data['contextMessage']);
    }

    public function testUpdateContext(): void
    {
        $contextId = 'cot_66b46fefe29d5';
        $newMessage = 'Updated context message';
        $dto = new ContextUpdateDTO($contextId, $newMessage);

        $response = $this->client->updateContext($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['contextId']));
        $this->assertTrue(isset($data['contextMessage']));
        $this->assertEquals('I\'m new context', $data['contextMessage']);
    }

    public function testDeleteContext(): void
    {
        $contextId = 'cot_66b46fefe29d5';
        $dto = new ContextDeleteDTO($contextId);

        $response = $this->client->deleteContext($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['message']));
        $this->assertEquals('the context was deleted', $data['message']);
    }

    public function testConstructorUsesProvidedApiUrls(): void
    {
        $httpClientMock = $this->getMockBuilder(HttpClientInterface::class)
            ->onlyMethods(['request', 'stream'])
            ->getMock();

        $customApiUrls = $this->getMockBuilder(ApiUrls::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var MockObject&HttpClientInterface $httpClientMock */
        /** @var MockObject&ApiUrls $customApiUrls */
        $client = new ChatbotClient($httpClientMock, $customApiUrls);

        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('apiUrls');
        $property->setAccessible(true);

        $this->assertSame($customApiUrls, $property->getValue($client));
    }
}
