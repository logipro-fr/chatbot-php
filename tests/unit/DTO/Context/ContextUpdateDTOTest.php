<?php

namespace ChatbotPhp\Tests\Unit\DTO\Context;

use ChatbotPhp\DTO\Context\ContextUpdateDTO;
use PHPUnit\Framework\TestCase;

class ContextUpdateDTOTest extends TestCase
{
    public function testConstructor(): void
    {
        $id = 'cot_66b46fefe29d5';
        $newMessage = 'Updated context message';

        $dto = new ContextUpdateDTO($id, $newMessage);

        $this->assertSame($id, $dto->id);
        $this->assertSame($newMessage, $dto->newMessage);
    }

    public function testConstructorWithSpecialCharacters(): void
    {
        $id = 'cot_66b46fefe29d5';
        $newMessage = 'Message with special chars: éàçù';

        $dto = new ContextUpdateDTO($id, $newMessage);

        $this->assertSame($id, $dto->id);
        $this->assertSame($newMessage, $dto->newMessage);
    }
}
