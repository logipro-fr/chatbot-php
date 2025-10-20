<?php

namespace ChatbotPhp\DTO\File;

class FileUploadDTO
{
    public string $filePath;
    public string $purpose;

    public function __construct(string $filePath, string $purpose = 'assistants')
    {
        $this->filePath = $filePath;
        $this->purpose = $purpose;
    }
}
