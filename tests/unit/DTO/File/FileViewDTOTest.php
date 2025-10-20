<?php

namespace ChatbotPhp\Tests\Unit\DTO\File;

use ChatbotPhp\DTO\File\FileViewDTO;
use PHPUnit\Framework\TestCase;

class FileViewDTOTest extends TestCase
{
    public function testConstructor(): void
    {
        $fileId = 'fil-abc123';

        $dto = new FileViewDTO($fileId);

        $this->assertSame($fileId, $dto->fileId);
    }

    public function testConstructorWithSpecialCharacters(): void
    {
        $fileId = 'fil-abc123-éàçù';

        $dto = new FileViewDTO($fileId);

        $this->assertSame($fileId, $dto->fileId);
    }

    public function testConstructorWithLongId(): void
    {
        $fileId = 'fil-very-long-file-id-that-might-cause-issues-123456789';

        $dto = new FileViewDTO($fileId);

        $this->assertSame($fileId, $dto->fileId);
    }
}
