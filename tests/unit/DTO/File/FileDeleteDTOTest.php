<?php

namespace ChatbotPhp\Tests\Unit\DTO\File;

use ChatbotPhp\DTO\File\FileDeleteDTO;
use PHPUnit\Framework\TestCase;

class FileDeleteDTOTest extends TestCase
{
    public function testConstructor(): void
    {
        $fileId = 'fil-abc123';

        $dto = new FileDeleteDTO($fileId);

        $this->assertSame($fileId, $dto->fileId);
    }

    public function testConstructorWithSpecialCharacters(): void
    {
        $fileId = 'fil-abc123-éàçù';

        $dto = new FileDeleteDTO($fileId);

        $this->assertSame($fileId, $dto->fileId);
    }

    public function testConstructorWithLongId(): void
    {
        $fileId = 'fil-very-long-file-id-that-might-cause-issues-123456789';

        $dto = new FileDeleteDTO($fileId);

        $this->assertSame($fileId, $dto->fileId);
    }
}
