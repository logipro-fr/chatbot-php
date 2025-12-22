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
        if ($method == 'GET' && str_contains($url, '/v1/contexts?Id=')) {
            return $this->getV1ContextsMockResponse();
        }
        if ($method == 'PATCH' && str_ends_with($url, '/v1/contexts')) {
            return $this->patchV1ContextsMockResponse();
        }
        if ($method == 'DELETE' && str_ends_with($url, '/v1/contexts')) {
            return $this->deleteV1ContextsMockResponse();
        }
        if ($method == 'POST' && str_ends_with($url, '/v1/file/upload')) {
            return $this->postV1FileUploadMockResponse();
        }
        if ($method == 'GET' && str_ends_with($url, '/v1/file/list')) {
            return $this->getV1FilesMockResponse();
        }
        if ($method == 'GET' && str_contains($url, '/v1/file/get')) {
            return $this->getV1FileViewMockResponse();
        }
        if ($method == 'DELETE' && str_ends_with($url, '/v1/file/delete')) {
            return $this->deleteV1FileMockResponse();
        }
        if ($method == 'POST' && str_ends_with($url, '/v1/assistant/create-from-context')) {
            return $this->postV1AssistantCreateMockResponse();
        }
        if (
            $method == 'GET' && str_contains($url, '/v1/assistant/')
            && !str_contains($url, '/files') && !str_contains($url, '/delete')
        ) {
            return $this->getV1AssistantViewMockResponse();
        }
        if ($method == 'PUT' && str_contains($url, '/v1/assistant/') && str_ends_with($url, '/files')) {
            return $this->putV1AssistantAttachFileMockResponse();
        }
        if ($method == 'DELETE' && str_contains($url, '/v1/assistant/') && str_ends_with($url, '/delete')) {
            return $this->deleteV1AssistantMockResponse();
        }
        if ($method == 'POST' && str_ends_with($url, '/v1/conversations/Make')) {
            return $this->postV1ConversationsMakeMockResponse();
        }
        if ($method == 'POST' && str_ends_with($url, '/v1/conversations/Continue')) {
            return $this->postV1ConversationsContinueMockResponse();
        }
        if ($method == 'GET' && str_contains($url, '/v1/conversations?Id=')) {
            return $this->getV1ConversationsViewMockResponse();
        }
        if ($method == 'POST' && str_ends_with($url, '/v1/assistant/conversation')) {
            return $this->postV1AssistantConversationMockResponse();
        }
        if ($method == 'POST' && str_ends_with($url, '/v1/assistant/conversation/continue')) {
            return $this->postV1AssistantConversationContinueMockResponse();
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

    private function patchV1ContextsMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Context/updateContext.json');
        return new MockResponse($response);
    }

    private function deleteV1ContextsMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Context/deleteContext.json');
        return new MockResponse($response);
    }

    private function postV1FileUploadMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/File/uploadFile.json');
        return new MockResponse($response);
    }

    private function getV1FilesMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/File/listFiles.json');
        return new MockResponse($response);
    }

    private function getV1FileViewMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/File/viewFile.json');
        return new MockResponse($response);
    }

    private function deleteV1FileMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/File/deleteFile.json');
        return new MockResponse($response);
    }

    private function postV1AssistantCreateMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Assistant/create.json');
        return new MockResponse($response);
    }

    private function getV1AssistantViewMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Assistant/view.json');
        return new MockResponse($response);
    }

    private function putV1AssistantAttachFileMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Assistant/update.json');
        return new MockResponse($response);
    }

    private function deleteV1AssistantMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Assistant/delete.json');
        return new MockResponse($response);
    }

    private function postV1ConversationsMakeMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Conversation/makeConversation.json');
        return new MockResponse($response);
    }

    private function postV1ConversationsContinueMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Conversation/continueConversation.json');
        return new MockResponse($response);
    }

    private function getV1ConversationsViewMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Conversation/viewConversation.json');
        return new MockResponse($response);
    }

    private function postV1AssistantConversationMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Thread/createThread.json');
        return new MockResponse($response);
    }

    private function postV1AssistantConversationContinueMockResponse(): MockResponse
    {
        $response = $this->readResponseJson('/Thread/continueThread.json');
        return new MockResponse($response);
    }
}
