<?php

namespace ChatbotPhp;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Chatbot
{
    public static function client(?string $baseUrl = null): ChatbotClient
    {
        $httpClient = new CurlHttpClient();
        $apiUrls = $baseUrl !== null ? new ApiUrls($baseUrl) : new ApiUrls();

        return new ChatbotClient($httpClient, $apiUrls);
    }
}
