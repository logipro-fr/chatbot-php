<?php

namespace ChatbotPhp\Tests\Resources;

use ChatbotPhp\DTO\File\FileDeleteDTO;
use ChatbotPhp\DTO\File\FileUploadDTO;
use ChatbotPhp\DTO\File\FileViewDTO;
use ChatbotPhp\Resources\Files;
use ChatbotPhp\Services\FileService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FilesTest extends TestCase
{
    private Files $files;
    /** @var FileService&MockObject */
    private $fileService;

    protected function setUp(): void
    {
        $this->fileService = $this->createMock(FileService::class);
        $this->files = new Files($this->fileService);
    }

    public function testUpload(): void
    {
        $filePath = '/path/to/file.pdf';
        $purpose = 'assistants';
        $expectedResponse = '{"success":true,"data":{"file_id":"file-123"}}';

        $this->fileService
            ->expects($this->once())
            ->method('upload')
            ->with($this->callback(function (FileUploadDTO $dto) use ($filePath, $purpose) {
                return $dto->filePath === $filePath && $dto->purpose === $purpose;
            }))
            ->willReturn($expectedResponse);

        $result = $this->files->upload([
            'file_path' => $filePath,
            'purpose' => $purpose
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testUploadWithDefaultPurpose(): void
    {
        $filePath = '/path/to/file.pdf';
        $expectedResponse = '{"success":true,"data":{"file_id":"file-123"}}';

        $this->fileService
            ->expects($this->once())
            ->method('upload')
            ->with($this->callback(function (FileUploadDTO $dto) use ($filePath) {
                return $dto->filePath === $filePath && $dto->purpose === 'assistants';
            }))
            ->willReturn($expectedResponse);

        $result = $this->files->upload([
            'file_path' => $filePath
        ]);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testList(): void
    {
        $expectedResponse = '{"success":true,"data":{"files":[]}}';

        $this->fileService
            ->expects($this->once())
            ->method('list')
            ->willReturn($expectedResponse);

        $result = $this->files->list();

        $this->assertEquals($expectedResponse, $result);
    }

    public function testRetrieve(): void
    {
        $fileId = 'file-123';
        $expectedResponse = '{"success":true,"data":{"file":{"id":"file-123"}}}';

        $this->fileService
            ->expects($this->once())
            ->method('view')
            ->with($this->callback(function (FileViewDTO $dto) use ($fileId) {
                return $dto->fileId === $fileId;
            }))
            ->willReturn($expectedResponse);

        $result = $this->files->retrieve($fileId);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testDelete(): void
    {
        $fileId = 'file-123';
        $expectedResponse = '{"success":true,"data":{"message":"File deleted"}}';

        $this->fileService
            ->expects($this->once())
            ->method('delete')
            ->with($this->callback(function (FileDeleteDTO $dto) use ($fileId) {
                return $dto->fileId === $fileId;
            }))
            ->willReturn($expectedResponse);

        $result = $this->files->delete($fileId);

        $this->assertEquals($expectedResponse, $result);
    }
}
