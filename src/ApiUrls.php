<?php

namespace ChatbotPhp;

class ApiUrls
{
    public const BASE_URL_PROD = 'https://dev.chatbot.logipro.fr';
    public const PREFIX_API = '/api/external';

    public const CREATE_CONVERSATION = '/v1/conversations/Make';
    public const CONTINUE_CONVERSATION = '/v1/conversations/Continue';
    public const VIEW_CONVERSATION = '/v1/conversations';
    public const LIST_CONVERSATIONS = '/v1/conversations/list';

    public const CREATE_CONTEXT = '/v1/context/Make';
    public const VIEW_CONTEXT = '/v1/contexts';
    public const UPDATE_CONTEXT = '/v1/contexts';
    public const DELETE_CONTEXT = '/v1/contexts';

    public const UPLOAD_FILE = '/v1/file/upload';
    public const LIST_FILES = '/v1/file/list';
    public const VIEW_FILE = '/v1/file/get';
    public const DELETE_FILE = '/v1/file/delete';

    public const CREATE_ASSISTANT = '/v1/assistant/create-from-context';
    public const ATTACH_ASSISTANT_FILE = '/v1/assistant/%s/files';
    public const DETACH_ASSISTANT_FILE = '/v1/assistant/%s/files/%s';
    public const VIEW_ASSISTANT = '/v1/assistant/%s';
    public const DELETE_ASSISTANT = '/v1/assistant/%s/delete';

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
        return $this->baseUrl . self::VIEW_CONTEXT . '?Id=' . urlencode($contextId);
    }

    public function updateContext(): string
    {
        return $this->baseUrl . self::UPDATE_CONTEXT;
    }

    public function deleteContext(): string
    {
        return $this->baseUrl . self::DELETE_CONTEXT;
    }

    public function makeConversation(): string
    {
        return $this->baseUrl . self::CREATE_CONVERSATION;
    }

    public function continueConversation(): string
    {
        return $this->baseUrl . self::CONTINUE_CONVERSATION;
    }

    public function viewConversation(string $conversationId): string
    {
        return $this->baseUrl . self::VIEW_CONVERSATION . '?Id=' . urlencode($conversationId);
    }

    public function listConversations(string $assistantId): string
    {
        return $this->baseUrl . self::LIST_CONVERSATIONS . '?assistantId=' . urlencode($assistantId);
    }

    public function uploadFile(): string
    {
        return $this->baseUrl . self::UPLOAD_FILE;
    }

    public function listFiles(): string
    {
        return $this->baseUrl . self::LIST_FILES;
    }

    public function viewFile(string $fileId): string
    {
        return $this->baseUrl . self::VIEW_FILE . '?Id=' . urlencode($fileId);
    }

    public function deleteFile(): string
    {
        return $this->baseUrl . self::DELETE_FILE;
    }

    public function createAssistant(): string
    {
        return $this->baseUrl . self::CREATE_ASSISTANT;
    }

    public function viewAssistant(string $assistantId): string
    {
        return $this->baseUrl . sprintf(self::VIEW_ASSISTANT, $assistantId);
    }

    public function attachAssistantFiles(string $assistantId): string
    {
        return $this->baseUrl . sprintf(self::ATTACH_ASSISTANT_FILE, $assistantId);
    }

    public function detachAssistantFiles(string $assistantId, string $fileId): string
    {
        return $this->baseUrl . sprintf(self::DETACH_ASSISTANT_FILE, $assistantId, $fileId);
    }

    public function deleteAssistant(string $assistantId): string
    {
        return $this->baseUrl . sprintf(self::DELETE_ASSISTANT, $assistantId);
    }

    public function createThread(): string
    {
        return $this->baseUrl . self::CREATE_THREAD;
    }

    public function continueThread(): string
    {
        return $this->baseUrl . self::CONTINUE_THREAD;
    }
}
