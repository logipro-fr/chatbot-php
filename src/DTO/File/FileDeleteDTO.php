<?php

namespace ChatbotPhp\DTO\File;

class FileDeleteDTO
{
    public string $fileId;

    public function __construct(string $fileId)
    {
        $this->fileId = $fileId;
    }
}
