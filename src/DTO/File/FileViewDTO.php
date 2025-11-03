<?php

namespace ChatbotPhp\DTO\File;

class FileViewDTO
{
    public string $fileId;

    public function __construct(string $fileId)
    {
        $this->fileId = $fileId;
    }
}
