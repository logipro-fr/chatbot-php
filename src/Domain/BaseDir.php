<?php

namespace ChatbotPhp\Domain;

class BaseDir
{
    private const BASE_DIR = __DIR__ . '/../..';

    public static function getFullPath(string $relativePath): string
    {
        $basePath = sprintf('%s/', realpath(self::BASE_DIR));
        return $basePath . $relativePath;
    }
}
