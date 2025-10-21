<?php

namespace ChatbotPhp\Tests\Services;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\DTO\File\FileUploadDTO;
use ChatbotPhp\DTO\File\FileViewDTO;
use ChatbotPhp\DTO\File\FileDeleteDTO;
use ChatbotPhp\Services\FileService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FileServiceTest extends TestCase
{
    public function testUploadFileWithSuccess(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_file');
        file_put_contents($tempFile, 'test content');

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": {"file_id": "fil-abc123", "filename": "document.pdf", ' .
                '"purpose": "assistants", "size": 1024}}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new FileService($mockHttpClient, new ApiUrls());
        $dto = new FileUploadDTO($tempFile, 'assistants');

        $result = $service->upload($dto);

        $this->assertEquals('{"success": true, "data": {"file_id": "fil-abc123", "filename": "document.pdf", ' .
            '"purpose": "assistants", "size": 1024}}', $result);

        unlink($tempFile);
    }

    public function testListFilesWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": [{"file_id": "fil-abc123", "filename": "document.pdf", ' .
                '"purpose": "assistants", "size": 1024}]}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new FileService($mockHttpClient, new ApiUrls());

        $result = $service->list();

        $this->assertEquals('{"success": true, "data": [{"file_id": "fil-abc123", "filename": "document.pdf", ' .
            '"purpose": "assistants", "size": 1024}]}', $result);
    }

    public function testViewFileWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": {"file_id": "fil-abc123", "filename": "document.pdf", ' .
                '"purpose": "assistants", "size": 1024}}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new FileService($mockHttpClient, new ApiUrls());
        $dto = new FileViewDTO('fil-abc123');

        $result = $service->view($dto);

        $this->assertEquals('{"success": true, "data": {"file_id": "fil-abc123", "filename": "document.pdf", ' .
            '"purpose": "assistants", "size": 1024}}', $result);
    }

    public function testDeleteFileWithSuccess(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse
            ->method('getContent')
            ->willReturn('{"success": true, "data": {"message": "the file was deleted"}}');

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockHttpClient->method('request')->willReturn($mockResponse);

        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new FileService($mockHttpClient, new ApiUrls());
        $dto = new FileDeleteDTO('fil-abc123');

        $result = $service->delete($dto);

        $this->assertEquals('{"success": true, "data": {"message": "the file was deleted"}}', $result);
    }

    public function testUploadFileWithNonExistentFile(): void
    {
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        /** @var MockObject&HttpClientInterface $mockHttpClient */
        $service = new FileService($mockHttpClient, new ApiUrls());

        $nonExistentPath = '/path/to/non-existent-file.pdf';
        $dto = new FileUploadDTO($nonExistentPath, 'assistants');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le fichier n'existe pas : " . $nonExistentPath);

        $service->upload($dto);
    }
}
