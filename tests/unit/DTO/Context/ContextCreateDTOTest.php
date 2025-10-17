<?php

namespace ChatbotPhp\Tests\DTO\Context;

use ChatbotPhp\DTO\Context\ContextCreateDTO;
use PHPUnit\Framework\TestCase;

class ContextCreateDTOTest extends TestCase
{
    public function testCreateContextCreateDTO(): void
    {
        $contextMessage = "Répondez comme un commercial nextSign";

        $dto = new ContextCreateDTO($contextMessage);

        $this->assertEquals($contextMessage, $dto->contextMessage);
    }

    public function testCreateContextCreateDTOWithEmptyMessage(): void
    {
        $contextMessage = "";

        $dto = new ContextCreateDTO($contextMessage);

        $this->assertEquals("", $dto->contextMessage);
    }

    public function testCreateContextCreateDTOWithLongMessage(): void
    {
        $contextMessage = str_repeat("a", 1000);

        $dto = new ContextCreateDTO($contextMessage);

        $this->assertEquals($contextMessage, $dto->contextMessage);
    }
}
