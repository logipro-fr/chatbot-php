<?php

namespace ChatbotPhp\Services;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\DTO\File\FileUploadDTO;
use ChatbotPhp\DTO\File\FileViewDTO;
use ChatbotPhp\DTO\File\FileDeleteDTO;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FileService
{
    private HttpClientInterface $httpClient;
    private ApiUrls $apiUrls;

    public function __construct(
        HttpClientInterface $httpClient,
        ApiUrls $apiUrls
    ) {
        $this->httpClient = $httpClient;
        $this->apiUrls = $apiUrls;
    }

    public function upload(FileUploadDTO $dto): string
    {
        $response = $this->httpClient->request('POST', $this->apiUrls->uploadFile(), [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($dto->filePath, 'r'),
                    'filename' => basename($dto->filePath)
                ],
                [
                    'name' => 'purpose',
                    'contents' => $dto->purpose
                ]
            ]
        ]);
        return $response->getContent();
    }

    public function list(): string
    {
        $response = $this->httpClient->request('GET', $this->apiUrls->listFiles());
        return $response->getContent();
    }

    public function view(FileViewDTO $dto): string
    {
        $response = $this->httpClient->request('GET', $this->apiUrls->viewFile($dto->fileId));
        return $response->getContent();
    }

    public function delete(FileDeleteDTO $dto): string
    {
        $response = $this->httpClient->request('DELETE', $this->apiUrls->deleteFile(), [
            'json' => [
                'id' => $dto->fileId
            ]
        ]);
        return $response->getContent();
    }
}
