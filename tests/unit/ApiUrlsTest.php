<?php

namespace Tests\Unit;

use ChatbotPhp\ApiUrls;
use PHPUnit\Framework\TestCase;

class ApiUrlsTest extends TestCase
{
    public function testGetApiUrls(): void
    {
        $apiurls = new ApiUrls();
        $baseUrl = $apiurls->getBaseUrl();
        $this->assertSame(ApiUrls::BASE_URL_PROD, $baseUrl);
    }

    public function testNewBaseUrl(): void
    {
        $apiurls = new ApiUrls('https://nginx');
        $baseUrl = $apiurls->getBaseUrl();
        $this->assertSame('https://nginx', $baseUrl);
    }

    public function testCreateContext(): void
    {
        $this->assertSame(
            ApiUrls::BASE_URL_PROD . ApiUrls::CREATE_CONTEXT,
            (new ApiUrls())->createContext()
        );
    }

    public function testViewContext(): void
    {
        $contextId = 'cot_66b46fefe29d5';
        $expectedUrl = ApiUrls::BASE_URL_PROD . ApiUrls::VIEW_CONTEXT . '?Id=' . urlencode($contextId);

        $this->assertSame(
            $expectedUrl,
            (new ApiUrls())->viewContext($contextId)
        );
    }

    public function testUpdateContext(): void
    {
        $this->assertSame(
            ApiUrls::BASE_URL_PROD . ApiUrls::UPDATE_CONTEXT,
            (new ApiUrls())->updateContext()
        );
    }

    public function testDeleteContext(): void
    {
        $this->assertSame(
            ApiUrls::BASE_URL_PROD . ApiUrls::DELETE_CONTEXT,
            (new ApiUrls())->deleteContext()
        );
    }

    public function testCreateAssistant(): void
    {
        $this->assertSame(
            ApiUrls::BASE_URL_PROD . ApiUrls::CREATE_ASSISTANT,
            (new ApiUrls())->createAssistant()
        );
    }

    public function testViewAssistant(): void
    {
        $assistantId = 'ast_abc123';
        $expectedUrl = ApiUrls::BASE_URL_PROD . sprintf(ApiUrls::VIEW_ASSISTANT, $assistantId);

        $this->assertSame(
            $expectedUrl,
            (new ApiUrls())->viewAssistant($assistantId)
        );
    }

    public function testAttachAssistantFiles(): void
    {
        $assistantId = 'ast_abc123';
        $expectedUrl = ApiUrls::BASE_URL_PROD . sprintf(ApiUrls::ATTACH_ASSISTANT_FILE, $assistantId);

        $this->assertSame(
            $expectedUrl,
            (new ApiUrls())->attachAssistantFiles($assistantId)
        );
    }

    public function testDetachAssistantFiles(): void
    {
        $assistantId = 'ast_abc123';
        $fileId = 'file-abc123';
        $expectedUrl = ApiUrls::BASE_URL_PROD . sprintf(ApiUrls::DETACH_ASSISTANT_FILE, $assistantId, $fileId);

        $this->assertSame(
            $expectedUrl,
            (new ApiUrls())->detachAssistantFiles($assistantId, $fileId)
        );
    }

    public function testDeleteAssistant(): void
    {
        $assistantId = 'ast_abc123';
        $expectedUrl = ApiUrls::BASE_URL_PROD . sprintf(ApiUrls::DELETE_ASSISTANT, $assistantId);

        $this->assertSame(
            $expectedUrl,
            (new ApiUrls())->deleteAssistant($assistantId)
        );
    }

    public function testListConversations(): void
    {
        $assistantId = 'ast_abc123';
        $expectedUrl = ApiUrls::BASE_URL_PROD . ApiUrls::LIST_CONVERSATIONS . '?assistantId=' . urlencode($assistantId);

        $this->assertSame(
            $expectedUrl,
            (new ApiUrls())->listConversations($assistantId)
        );
    }
}
