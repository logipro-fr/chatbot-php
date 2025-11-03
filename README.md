# Chatbot PHP

Client PHP for Chatbot API

## Install

```bash
git clone https://github.com/logipro-fr/chatbot-php
cd chatbot-php
./install --profile devlocal
```

## Usage

### 1. Initialisation

Créez un client en spécifiant l'URL de l'API :

```php
use ChatbotPhp\Chatbot;

$client = Chatbot::client('http://api.example.com');
```

Ou utilisez l'URL par défaut :

```php
$client = Chatbot::client();
```

### 2. Créer un contexte

Un contexte contient les instructions ou le contexte que l'assistant utilisera :

```php
$context = json_decode($client->contexts()->create([
    'context_message' => 'Vous êtes un assistant expert en documentation PHP.'
]), true);
$contextId = $context['data']['contextId'];
```

### 3. Uploader un fichier

Ajoutez des fichiers que l'assistant pourra utiliser :

```php
$file = json_decode($client->files()->upload([
    'file_path' => '/path/to/documentation.pdf',
    'purpose' => 'assistants'
]), true);
$fileId = $file['data']['file_id'];
```

### 4. Créer un assistant

Créez un assistant basé sur un contexte et des fichiers :

```php
$assistant = json_decode($client->assistants()->create([
    'context_id' => $contextId,
    'file_ids' => [$fileId]
]), true);
$assistantId = $assistant['data']['assistantId'];
```

### 5. Créer un thread (conversation avec assistant)

Démarrez une conversation avec votre assistant :

```php
$thread = json_decode($client->threads()->create([
    'ast_id' => $assistantId,
    'message' => 'Peux-tu m\'aider à documenter cette fonction PHP ?'
]), true);
$conversationId = $thread['data']['conversationId'];
$assistantMessage = $thread['data']['assistantMessage'];
echo $assistantMessage;
```

### 6. Continuer la conversation

Envoyez des messages supplémentaires dans le thread :

```php
$response = json_decode($client->threads()->continue([
    'conversation_id' => $conversationId,
    'message' => 'Peux-tu m\'expliquer plus en détail ?'
]), true);
$responseMessage = $response['data']['message'];
echo $responseMessage;
```

### Ressources disponibles

#### Contexts - Gestion des contextes

```php
$client->contexts()->create(['context_message' => '...']);
$client->contexts()->retrieve($contextId);
$client->contexts()->update(['id' => '...', 'new_message' => '...']);
$client->contexts()->delete($contextId);
```

#### Files - Gestion des fichiers

```php
$client->files()->upload(['file_path' => '...', 'purpose' => 'assistants']);
$client->files()->list();
$client->files()->retrieve($fileId);
$client->files()->delete($fileId);
```

#### Assistants - Gestion des assistants

```php
$client->assistants()->create(['context_id' => '...', 'file_ids' => [...]]);
$client->assistants()->retrieve($assistantId);
$client->assistants()->update(['assistant_id' => '...', 'file_ids' => [...]]);
$client->assistants()->delete($assistantId);
```

#### Conversations - Gestion des conversations simples

```php
$client->conversations()->create(['prompt' => '...', 'lm_name' => '...', 'context' => '...']);
$client->conversations()->continue(['prompt' => '...', 'conv_id' => '...', 'lm_name' => '...']);
$client->conversations()->retrieve($conversationId);
```

#### Threads - Gestion des threads (conversations avec assistant)

```php
$client->threads()->create(['ast_id' => '...', 'message' => '...']);
$client->threads()->continue(['conversation_id' => '...', 'message' => '...']);
```

## Contributing

### Requirements

* docker
* git

### Add a .env.local file

To install locally or on a development server, be careful with the following environment variables:
* DATA_PATH: path where data is stored; must be inside the project (default: ./data)
* DATA_PATH_STORE: path for backups; generally outside the project (default: ../data/chatbot-php)
* REMOVE_DATABASE_WHEN_INSTALL: remove database during install (default: false)
* BUILD_WHEN_INSTALL: build application during install (default: false)
* DOCKER_DEV: run development-specific containers (default: false)
* DOCKER_PHP_BUILT_IMAGE: application prebuilt Docker image
* OPTIONAL_VOLUME: mount a local volume for localhost development (default: empty)
* LOCALDEV_WORKING_DIR: working directory useful for development (default: undefined)
* URL_API: override the base URL used by internal API clients (default: empty)
* PULL_POLICY: policy for pulling the PHP built image on start (default: missing)

Typical local development .env.local:

```
DATA_PATH=./data
REMOVE_DATABASE_WHEN_INSTALL=true
BUILD_WHEN_INSTALL=true
DOCKER_DEV=true
OPTIONAL_VOLUME=.:/var/chatbot-php
LOCALDEV_WORKING_DIR=true
URL_API=http://nginx
PULL_POLICY=never
```

Typical server development .env.local:
```
DATA_PATH=../data
REMOVE_DATABASE_WHEN_INSTALL=true
DOCKER_DEV=true
DOCKER_PHP_BUILT_IMAGE= 
URL_API=https://dev.your-app.tld
```

For production and pre-production, a .env.local MUST NOT exist because default variables target the production environment.
However, this project includes a frontend that calls the API, so in pre-production you need a .env.local to override `URL_API`.

Typical pre-production .env.local:
```
URL_API=https://preprod.your-app.tld
```


## Tests

### Unit tests

```bash
bin/phpunit
```

We use Test-Driven Development (TDD) principles and good practices.

### Integration tests
Integration tests are all test categories other than unit tests.

```bash
bin/integration
```

### Acceptance tests
Acceptance tests are integration tests that verify the application against feature specifications.
Gherkin is the specification language; Behat is the PHP runner.

```bash
bin/behat
```


## Manual tests

```bash
./start
```
Then open 172.17.0.1:35080/ in your browser.

```bash
./stop
```

## Quality

Some indicators we aim for:

* phpcs PSR12
* phpstan level 10
* coverage >=100%
* infection MSI >=100%

Quick check:
```bash
./codecheck
```

Check coverage:
```bash
bin/phpunit --coverage-html var
```
Then open `var/index.html` in your browser.

Check infection:
```bash
bin/infection
```
Then open `var/infection.html` in your browser.