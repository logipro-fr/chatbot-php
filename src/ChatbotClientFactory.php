<?php

namespace ChatbotPhp;

use ChatbotPhp\Domain\BaseDir;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

use function Safe\file_get_contents;

class ChatbotClientFactory
{
    private const RESPONSE_JSON_PATH = '/src/ResponseJSON';

    public function createMockChatbot(): ChatbotClient
    {
        $callable = function (string $method, string $url, array $options): MockResponse {
            return $this->callableResponse($method, $url, $options);
        };
        return new ChatbotClient(new MockHttpClient($callable));
    }

    /**
     * @param array<mixed, mixed> $options
     */
    private function callableResponse(string $method, string $url, array $options): MockResponse
    {
        if ($method == 'POST' && str_ends_with($url, '/v1/context/Make')) {
            return $this->postV1ContextMakeMockResponse();
        }
        if ($method == 'GET' && str_contains($url, '/v1/contexts?id=')) {
            return $this->getV1ContextsMockResponse();
        }
        if ($method == 'PUT' && str_ends_with($url, '/v1/contexts')) {
            return $this->putV1ContextsMockResponse();
        }
        if ($method == 'DELETE' && str_ends_with($url, '/v1/contexts')) {
            return $this->deleteV1ContextsMockResponse();
        }
        throw new BadRequestException();
    }

    private function readResponseJson(string $relativePath): string
    {
        $path = BaseDir::getFullPath(self::RESPONSE_JSON_PATH . $relativePath);
        $content = file_get_contents($path);
        return $content;
    }

    private function postV1ContextMakeMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Context/createContext.json');
        return new MockResponse($response);
    }

    private function getV1ContextsMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Context/viewContext.json');
        return new MockResponse($response);
    }

    private function putV1ContextsMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Context/updateContext.json');
        return new MockResponse($response);
    }

    private function deleteV1ContextsMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Context/deleteContext.json');
        return new MockResponse($response);
    }
}
