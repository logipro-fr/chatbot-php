<?php

namespace ChatbotPhp\Resources;

use ChatbotPhp\DTO\File\FileDeleteDTO;
use ChatbotPhp\DTO\File\FileUploadDTO;
use ChatbotPhp\DTO\File\FileViewDTO;
use ChatbotPhp\Services\FileService;

class Files
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function upload(array $parameters): string
    {
        /** @var string $filePath */
        $filePath = $parameters['file_path'];
        $purpose = 'assistants';
        if (isset($parameters['purpose'])) {
            /** @var string $purpose */
            $purpose = $parameters['purpose'];
        }
        $dto = new FileUploadDTO($filePath, $purpose);

        return $this->fileService->upload($dto);
    }

    public function list(): string
    {
        return $this->fileService->list();
    }

    public function retrieve(string $fileId): string
    {
        $dto = new FileViewDTO($fileId);

        return $this->fileService->view($dto);
    }

    public function delete(string $fileId): string
    {
        $dto = new FileDeleteDTO($fileId);

        return $this->fileService->delete($dto);
    }
}
