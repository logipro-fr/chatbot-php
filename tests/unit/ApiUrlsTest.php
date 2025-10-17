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
        $expectedUrl = ApiUrls::BASE_URL_PROD . ApiUrls::VIEW_CONTEXT . '?id=' . urlencode($contextId);

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
}
