<?php

namespace Tests\Integration;

use ChatbotPhp\DTO\File\FileUploadDTO;
use Symfony\Component\HttpClient\Exception\ClientException;

class FileIntegrationTest extends BaseIntegrationTestCase
{
    private string $testFilePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testFilePath = __DIR__ . '/../resources/test-document.pdf';
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testUploadFile(): void
    {
        $dto = new FileUploadDTO($this->testFilePath, 'assistants');

        $result = $this->chatbotClient->uploadFile($dto);

        $responseData = $this->assertSuccessfulResponse($result);

        $this->assertIsArray($responseData['data']);
        $this->assertArrayHasKey('file_id', $responseData['data']);
        $this->assertArrayHasKey('filename', $responseData['data']);
        $this->assertArrayHasKey('purpose', $responseData['data']);
        $this->assertArrayHasKey('size', $responseData['data']);

        $this->assertNotEmpty($responseData['data']['file_id']);
        $this->assertIsString($responseData['data']['file_id']);
        $this->assertStringStartsWith('file-', $responseData['data']['file_id']);

        $this->assertEquals('test-document.pdf', $responseData['data']['filename']);
        $this->assertEquals('assistants', $responseData['data']['purpose']);
        $this->assertIsInt($responseData['data']['size']);
        $this->assertGreaterThan(0, $responseData['data']['size']);
    }

    public function testUploadFileWithInvalidPurpose(): void
    {
        $dto = new FileUploadDTO($this->testFilePath, 'fine-tune');

        $this->expectException(ClientException::class);
        $this->chatbotClient->uploadFile($dto);
    }

    public function testUploadFileWithNonExistentFile(): void
    {
        $nonExistentPath = __DIR__ . '/../resources/non-existent-file.pdf';
        $dto = new FileUploadDTO($nonExistentPath, 'assistants');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Le fichier n'existe pas : " . $nonExistentPath);
        $this->chatbotClient->uploadFile($dto);
    }
}
