<?php

namespace ChatbotPhp;

class ApiUrls
{
    public const BASE_URL_PROD = 'https://api.chatbot-php.com';
    public const PREFIX_API = '/api/external';

    public const CREATE_CONVERSATION = '/v1/conversations/make';
    public const CONTINUE_CONVERSATION = '/v1/conversations/continue';
    public const VIEW_CONVERSATION = '/v1/conversations';

    public const CREATE_CONTEXT = '/v1/context/make';
    public const VIEW_CONTEXT = '/v1/contexts';
    public const UPDATE_CONTEXT = '/v1/contexts';
    public const DELETE_CONTEXT = '/v1/contexts';

    public const UPLOAD_FILE = '/v1/file/upload';
    public const LIST_FILES = '/v1/file/list';
    public const VIEW_FILE = '/v1/file/get';
    public const DELETE_FILE = '/v1/file/delete';

    public const CREATE_ASSISTANT = '/v1/assistant/create-from-context';
    public const UPDATE_ASSISTANT = '/v1/assistant/files';
    public const VIEW_ASSISTANT = '/v1/assistant';
    public const DELETE_ASSISTANT = '/v1/assistant/delete';

    public const CREATE_THREAD = '/v1/assistant/conversation';
    public const CONTINUE_THREAD = '/v1/assistant/conversation/continue';

    private string $baseUrl = self::BASE_URL_PROD;

    public function __construct(?string $baseUrl = null)
    {
        if ($baseUrl !== null) {
            $this->baseUrl = $baseUrl;
        }
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function createContext(): string
    {
        return $this->baseUrl . self::CREATE_CONTEXT;
    }

    public function viewContext(string $contextId): string
    {
        return $this->baseUrl . self::VIEW_CONTEXT . '?id=' . urlencode($contextId);
    }

    public function updateContext(): string
    {
        return $this->baseUrl . self::UPDATE_CONTEXT;
    }

    public function deleteContext(): string
    {
        return $this->baseUrl . self::DELETE_CONTEXT;
    }
}
