<?php

namespace ChatbotPhp\DTO\Conversation;

class ConversationContinueDTO
{
    public string $prompt;
    public string $convId;
    public string $lmName;

    public function __construct(string $prompt, string $convId, string $lmName)
    {
        $this->prompt = $prompt;
        $this->convId = $convId;
        $this->lmName = $lmName;
    }
}
