<?php

namespace Tests\ChatbotPhp;

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testEchoWelcome(): void
    {
        $this->expectOutputString('Welcome to Client PHP Chatbot API');
        require getcwd() . '/src/public/index.php';
    }
}
