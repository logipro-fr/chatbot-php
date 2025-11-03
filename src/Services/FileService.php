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
        if (!file_exists($dto->filePath)) {
            throw new \InvalidArgumentException("Le fichier n'existe pas : " . $dto->filePath);
        }

        $boundary = uniqid();
        $filename = basename($dto->filePath);
        $fileContent = file_get_contents($dto->filePath);

        $body = "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"file\"; filename=\"{$filename}\"\r\n";
        $body .= "Content-Type: application/octet-stream\r\n\r\n";
        $body .= $fileContent . "\r\n";
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Disposition: form-data; name=\"purpose\"\r\n\r\n";
        $body .= $dto->purpose . "\r\n";
        $body .= "--{$boundary}--\r\n";

        $response = $this->httpClient->request('POST', $this->apiUrls->uploadFile(), [
            'headers' => [
                'Content-Type' => "multipart/form-data; boundary={$boundary}"
            ],
            'body' => $body
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
