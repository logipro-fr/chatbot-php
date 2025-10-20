<?php

namespace ChatbotPhp\Tests\Unit\DTO\File;

use ChatbotPhp\DTO\File\FileUploadDTO;
use PHPUnit\Framework\TestCase;

class FileUploadDTOTest extends TestCase
{
    public function testConstructor(): void
    {
        $filePath = '/path/to/document.pdf';
        $purpose = 'assistants';

        $dto = new FileUploadDTO($filePath, $purpose);

        $this->assertSame($filePath, $dto->filePath);
        $this->assertSame($purpose, $dto->purpose);
    }

    public function testConstructorWithDefaultPurpose(): void
    {
        $filePath = '/path/to/document.pdf';

        $dto = new FileUploadDTO($filePath);

        $this->assertSame($filePath, $dto->filePath);
        $this->assertSame('assistants', $dto->purpose);
    }

    public function testConstructorWithSpecialCharacters(): void
    {
        $filePath = '/path/to/documents/rapport_éàçù.pdf';
        $purpose = 'assistants';

        $dto = new FileUploadDTO($filePath, $purpose);

        $this->assertSame($filePath, $dto->filePath);
        $this->assertSame($purpose, $dto->purpose);
    }

    public function testConstructorWithLongPath(): void
    {
        $filePath = '/very/long/path/to/a/file/with/a/very/long/name/that/might/cause/issues.pdf';
        $purpose = 'assistants';

        $dto = new FileUploadDTO($filePath, $purpose);

        $this->assertSame($filePath, $dto->filePath);
        $this->assertSame($purpose, $dto->purpose);
    }
}
