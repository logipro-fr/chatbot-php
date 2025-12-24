<?php

namespace ChatbotPhp\Tests;

use ChatbotPhp\ApiUrls;
use ChatbotPhp\ChatbotClient;
use ChatbotPhp\ChatbotClientFactory;
use ChatbotPhp\DTO\Assistant\AssistantCreateDTO;
use ChatbotPhp\DTO\Assistant\AssistantDeleteDTO;
use ChatbotPhp\DTO\Assistant\AssistantAttachFileDTO;
use ChatbotPhp\DTO\Assistant\AssistantDetachFileDTO;
use ChatbotPhp\DTO\Assistant\AssistantViewDTO;
use ChatbotPhp\DTO\Context\ContextCreateDTO;
use ChatbotPhp\DTO\Context\ContextDeleteDTO;
use ChatbotPhp\DTO\Context\ContextUpdateDTO;
use ChatbotPhp\DTO\Conversation\ConversationMakeDTO;
use ChatbotPhp\DTO\Conversation\ConversationContinueDTO;
use ChatbotPhp\DTO\Conversation\ConversationViewDTO;
use ChatbotPhp\DTO\File\FileUploadDTO;
use ChatbotPhp\DTO\File\FileViewDTO;
use ChatbotPhp\DTO\File\FileDeleteDTO;
use ChatbotPhp\DTO\Thread\ThreadCreateDTO;
use ChatbotPhp\DTO\Thread\ThreadContinueDTO;
use ChatbotPhp\Resources\Assistants;
use ChatbotPhp\Resources\Contexts;
use ChatbotPhp\Resources\Conversations;
use ChatbotPhp\Resources\Files;
use ChatbotPhp\Resources\Threads;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\json_decode;

class ChatbotClientTest extends TestCase
{
    protected ChatbotClient $client;

    protected function setUp(): void
    {
        $chatbotClientFactory = new ChatbotClientFactory();
        $this->client = $chatbotClientFactory->createMockChatbot();
    }

    public function testCreateContext(): void
    {
        $contextMessage = 'Test context message';
        $dto = new ContextCreateDTO($contextMessage);

        $response = $this->client->createContext($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $data = $responseData['data'];
        $this->assertIsArray($data);
        /** @var string $contextId */
        $contextId = $data['contextId'];
        $this->assertStringStartsWith('con_', $contextId);
    }

    public function testCreateContextWithSpecialCharacters(): void
    {
        $contextMessage = 'Message with special chars: éàçù';
        $dto = new ContextCreateDTO($contextMessage);

        $response = $this->client->createContext($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $data = $responseData['data'];
        $this->assertIsArray($data);
        /** @var string $contextId */
        $contextId = $data['contextId'];
        $this->assertStringStartsWith('con_', $contextId);
    }

    public function testViewContext(): void
    {
        $contextId = 'cot_66b46fefe29d5';

        $response = $this->client->viewContext($contextId);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['contextMessage']));
        $this->assertEquals('I\'m a context', $data['contextMessage']);
    }

    public function testUpdateContext(): void
    {
        $contextId = 'cot_66b46fefe29d5';
        $newMessage = 'Updated context message';
        $dto = new ContextUpdateDTO($contextId, $newMessage);

        $response = $this->client->updateContext($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['contextId']));
        $this->assertTrue(isset($data['contextMessage']));
        $this->assertEquals('I\'m new context', $data['contextMessage']);
    }

    public function testDeleteContext(): void
    {
        $contextId = 'cot_66b46fefe29d5';
        $dto = new ContextDeleteDTO($contextId);

        $response = $this->client->deleteContext($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['message']));
        $this->assertEquals('the context was deleted', $data['message']);
    }



    public function testUploadFile(): void
    {
        $filePath = __DIR__ . '/../resources/test-document.pdf';
        $dto = new FileUploadDTO($filePath, 'assistants');

        $response = $this->client->uploadFile($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['file_id']));
        $this->assertTrue(isset($data['filename']));
        $this->assertTrue(isset($data['purpose']));
        $this->assertTrue(isset($data['size']));
        /** @var string $fileId */
        $fileId = $data['file_id'];
        $this->assertStringStartsWith('fil-', $fileId);
        $this->assertEquals('document.pdf', $data['filename']);
        $this->assertEquals('assistants', $data['purpose']);
    }

    public function testListFiles(): void
    {
        $response = $this->client->listFiles();

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['files']));
        $this->assertIsArray($data['files']);
    }

    public function testViewFile(): void
    {
        $fileId = 'file-abc123';
        $dto = new FileViewDTO($fileId);

        $response = $this->client->viewFile($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['file']));
        $file = $data['file'];
        $this->assertIsArray($file);
        $this->assertTrue(isset($file['id']));
        $this->assertTrue(isset($file['filename']));
        $this->assertTrue(isset($file['purpose']));
        $this->assertTrue(isset($file['bytes']));
    }

    public function testDeleteFile(): void
    {
        $fileId = 'file-abc123';
        $dto = new FileDeleteDTO($fileId);

        $response = $this->client->deleteFile($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['message']));
        $this->assertEquals('File deleted successfully', $data['message']);
    }

    public function testCreateAssistant(): void
    {
        $contextId = 'cot_68e7aeb0a1f43';
        $fileIds = ['file-KNpZsP3NBAajzDETBqZQpX'];
        $dto = new AssistantCreateDTO($contextId, $fileIds);

        $response = $this->client->createAssistant($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['assistantId']));
        $this->assertTrue(isset($data['externalAssistantId']));
        /** @var string $assistantId */
        $assistantId = $data['assistantId'];
        $this->assertStringStartsWith('ast_', $assistantId);
    }

    public function testCreateAssistantWithEmptyFileIds(): void
    {
        $contextId = 'cot_68e7aeb0a1f43';
        $dto = new AssistantCreateDTO($contextId);

        $response = $this->client->createAssistant($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['assistantId']));
        $this->assertTrue(isset($data['externalAssistantId']));
    }

    public function testViewAssistant(): void
    {
        $assistantId = 'ast_abc123';
        $dto = new AssistantViewDTO($assistantId);

        $response = $this->client->viewAssistant($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['assistantId']));
        $this->assertTrue(isset($data['name']));
        $this->assertTrue(isset($data['instructions']));
        $this->assertTrue(isset($data['externalAssistantId']));
        $this->assertTrue(isset($data['fileIds']));
        $this->assertIsArray($data['fileIds']);
    }

    public function testAttachAssistantFiles(): void
    {
        $assistantId = 'ast_abc123';
        $fileIds = ['fil-abc123', 'fil-def456'];
        $dto = new AssistantAttachFileDTO($assistantId, $fileIds);

        $response = $this->client->attachAssistantFiles($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['assistantId']));
        $this->assertTrue(isset($data['fileIds']));
        $this->assertIsArray($data['fileIds']);
    }

    public function testDetachAssistantFiles(): void
    {
        $assistantId = 'ast_abc123';
        $fileId = 'file-fil123';
        $dto = new AssistantDetachFileDTO($assistantId, $fileId);

        $response = $this->client->detachAssistantFiles($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['assistantId']));
        $this->assertTrue(isset($data['fileIds']));
        $this->assertIsArray($data['fileIds']);
    }


    public function testDeleteAssistant(): void
    {
        $assistantId = 'ast_68f638a37c2474.79919725';
        $dto = new AssistantDeleteDTO($assistantId);

        $response = $this->client->deleteAssistant($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['assistantId']));
        $this->assertTrue(isset($data['externalAssistantId']));
        $this->assertEquals('ast_68f638a37c2474.79919725', $data['assistantId']);
    }

    public function testMakeConversation(): void
    {
        $prompt = "Bonjour";
        $lmName = "GPTModel";
        $context = "cot_66b46fefe29d5";
        $dto = new ConversationMakeDTO($prompt, $lmName, $context);

        $response = $this->client->makeConversation($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['conversationId']));
        $this->assertTrue(isset($data['numberOfPairs']));
        $this->assertTrue(isset($data['botMessage']));
        /** @var string $conversationId */
        $conversationId = $data['conversationId'];
        $this->assertStringStartsWith('con_', $conversationId);
        $this->assertEquals(1, $data['numberOfPairs']);
        $this->assertIsString($data['botMessage']);
    }

    public function testContinueConversation(): void
    {
        $prompt = "Comment allez-vous?";
        $convId = "con_66680a4a5ee25";
        $lmName = "GPTModel";
        $dto = new ConversationContinueDTO($prompt, $convId, $lmName);

        $response = $this->client->continueConversation($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['conversationId']));
        $this->assertTrue(isset($data['numberOfPairs']));
        $this->assertTrue(isset($data['botMessage']));
        /** @var string $conversationId */
        $conversationId = $data['conversationId'];
        $this->assertStringStartsWith('con_', $conversationId);
        $this->assertIsInt($data['numberOfPairs']);
        $this->assertIsString($data['botMessage']);
        if (isset($responseData['description'])) {
            $this->assertIsString($responseData['description']);
        }
    }

    public function testViewConversation(): void
    {
        $conversationId = "con_66cd76e11574";
        $dto = new ConversationViewDTO($conversationId);

        $response = $this->client->viewConversation($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['contextId']));
        $this->assertTrue(isset($data['pairs']));
        /** @var string $contextId */
        $contextId = $data['contextId'];
        $this->assertStringStartsWith('cot_', $contextId);
        $this->assertIsArray($data['pairs']);
    }

    public function testCreateThread(): void
    {
        $astId = "ast_abc123";
        $message = "Peux-tu m'aider à documenter cette fonction PHP ?";
        $dto = new ThreadCreateDTO($astId, $message);

        $response = $this->client->createThread($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['conversationId']));
        $this->assertTrue(isset($data['assistantMessage']));
        /** @var string $conversationId */
        $conversationId = $data['conversationId'];
        $this->assertStringStartsWith('con_', $conversationId);
        $this->assertIsString($data['assistantMessage']);
    }

    public function testContinueThread(): void
    {
        $conversationId = "con_66680a4a5ee25";
        $message = "Peux-tu m'expliquer plus en détail ?";
        $dto = new ThreadContinueDTO($conversationId, $message);

        $response = $this->client->continueThread($dto);

        /** @var array<string, mixed> */
        $responseData = json_decode($response, true);
        $this->assertTrue(isset($responseData['success']));
        $this->assertTrue($responseData['success']);
        $data = $responseData['data'];
        $this->assertIsArray($data);
        $this->assertTrue(isset($data['conversationId']));
        $this->assertTrue(isset($data['message']));
        /** @var string $convId */
        $convId = $data['conversationId'];
        $this->assertStringStartsWith('con_', $convId);
        $this->assertIsString($data['message']);
        if (isset($responseData['description'])) {
            $this->assertIsString($responseData['description']);
        }
    }

    public function testAssistantsReturnsAssistantsInstance(): void
    {
        $assistants = $this->client->assistants();

        $this->assertInstanceOf(Assistants::class, $assistants);
    }

    public function testFilesReturnsFilesInstance(): void
    {
        $files = $this->client->files();

        $this->assertInstanceOf(Files::class, $files);
    }

    public function testConversationsReturnsConversationsInstance(): void
    {
        $conversations = $this->client->conversations();

        $this->assertInstanceOf(Conversations::class, $conversations);
    }

    public function testContextsReturnsContextsInstance(): void
    {
        $contexts = $this->client->contexts();

        $this->assertInstanceOf(Contexts::class, $contexts);
    }

    public function testThreadsReturnsThreadsInstance(): void
    {
        $threads = $this->client->threads();

        $this->assertInstanceOf(Threads::class, $threads);
    }
}
