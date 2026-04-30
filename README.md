# RAG Chatbot

A Retrieval-Augmented Generation (RAG) chatbot built with Laravel 12. 
Upload PDF documents and get context-aware AI answers powered by OpenAI.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 13 (PHP 8.3+) |
| UI | Livewire v4 |
| AI Orchestration | Prism PHP |
| LLM | OpenAI GPT-4o |
| Embeddings | OpenAI text-embedding-3-small |
| Vector Database | PostgreSQL + pgvector |
| PDF Parsing | smalot/pdfparser |
| Package Manager (PHP) | Composer |
| Package Manager (JS) | npm |

---

## Prerequisites

- PHP 8.3+
- Composer
- Node.js & npm
- PostgreSQL with pgvector extension
- [Laravel Herd](https://herd.laravel.com) (recommended) or XAMPP

---

## Installation

**Using Laravel Herd** — create a new project from the UI.

**Using terminal:**
```bash
composer create-project laravel/laravel rag-chatbot
cd rag-chatbot
```

---

## Dependencies

Install PHP packages:
```bash
composer require openai-php/client        # OpenAI API client
composer require echolabsdev/prism-php    # LLM orchestration
composer require livewire/livewire        # Livewire v4 UI
composer require smalot/pdfparser        # PDF text extraction
```

Install JS packages:
```bash
npm install
```

---

## Project Structure Setup

Run the following commands to scaffold the folder structure:

```bash
# Livewire component classes (PHP logic side)
mkdir -p app/Livewire/Chat
mkdir -p app/Livewire/Admin

# Service layer — ALL AI logic lives here, never in controllers
mkdir -p app/Services/RAG
mkdir -p app/Services/Document
mkdir -p app/Services/AI

# Background jobs
mkdir -p app/Jobs

# Livewire view templates
mkdir -p resources/views/livewire/chat
mkdir -p resources/views/livewire/admin
mkdir -p resources/views/layouts

# Document uploads storage
mkdir -p storage/app/documents

# Test folders mirroring the service structure
mkdir -p tests/Unit/Services
mkdir -p tests/Feature/Chat
mkdir -p tests/Feature/Document
```
---

## Environment Setup

Copy the example env file and configure it:
```bash
cp .env.example .env
php artisan key:generate
```

Key variables to set in your `.env`:
```env
DB_CONNECTION=pgsql
DB_DATABASE=rag_chatbot

OPENAI_API_KEY=sk-your-key-here
OPENAI_MODEL=gpt-4o
OPENAI_EMBED_MODEL=text-embedding-3-small
```

---

## Running the Project

```bash
# Run database migrations
php artisan migrate

# Start the development server
php artisan serve

# In a second terminal — start the queue worker (required for document processing)
php artisan queue:work
```

Then visit: `http://localhost:8000`

---

## Running Tests

```bash
php artisan test
```

---

## Author

Brian Mweu

## License

This project is licensed under the [MIT License](LICENSE).