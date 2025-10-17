<?php

namespace ChatbotPhp\Tests\Unit\DTO\Context;

use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use PHPUnit\Framework\TestCase;

class ContextDeleteDTOTest extends TestCase
{
    public function testConstructor(): void
    {
        $id = 'cot_66b46fefe29d5';
        
        $dto = new ContextDeleteDTO($id);
        
        $this->assertSame($id, $dto->id);
    }

    public function testConstructorWithSpecialCharacters(): void
    {
        $id = 'cot_66b46fefe29d5';
        
        $dto = new ContextDeleteDTO($id);
        
        $this->assertSame($id, $dto->id);
    }
}
