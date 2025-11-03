<?php

namespace ChatbotPhp\DTO\Conversation;

class ConversationMakeDTO
{
    public string $prompt;
    public string $lmName;
    public string $context;

    public function __construct(string $prompt, string $lmName, string $context)
    {
        $this->prompt = $prompt;
        $this->lmName = $lmName;
        $this->context = $context;
    }
}
