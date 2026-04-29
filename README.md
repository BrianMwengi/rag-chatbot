# 🤖 Laravel RAG Chatbot

A production-ready **Retrieval-Augmented Generation (RAG)** chatbot built with Laravel. Upload PDF/text documents, store embeddings in PostgreSQL via `pgvector`, and get context-aware AI answers powered by OpenAI.

---

## 📐 Architecture Overview

```
User Query
    │
    ▼
[Laravel API] ──► [Prism PHP Orchestrator]
                        │
              ┌─────────┴──────────┐
              ▼                    ▼
    [OpenAI Embeddings]    [pgvector Search]
              │                    │
              └─────────┬──────────┘
                        ▼
              [OpenAI GPT-4o Generation]
                        │
                        ▼
                  [Answer + Citations]
```

---

## ⚙️ Tech Stack

| Layer              | Technology                              |
|--------------------|-----------------------------------------|
| Backend Framework  | PHP 8.4 / Laravel 13                   |
| LLM               | OpenAI GPT-4o (via `openai-php/client`) |
| Orchestration      | Prism PHP                               |
| Vector Store       | PostgreSQL + `pgvector` extension        |
| Queue/Jobs         | Laravel Queues (Redis or database)      |
| PDF Parsing        | `smalot/pdfparser`                      |
| Containerisation   | Docker + Docker Compose                 |
| Deployment         | AWS (EC2 / ECS) or Railway/Render       |

---

## 📁 Project Structure

```
rag-chatbot/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── ChatController.php        # Handles user chat messages
│   │   │   │   └── DocumentController.php    # Handles document uploads
│   │   │   └── Admin/
│   │   │       └── KnowledgeBaseController.php
│   │   └── Requests/
│   │       ├── Chat/SendMessageRequest.php
│   │       └── Document/UploadDocumentRequest.php
│   ├── Models/
│   │   ├── Document.php                      # Uploaded document record
│   │   ├── DocumentChunk.php                 # Chunked text + embedding vector
│   │   └── Conversation.php                  # Chat session + message history
│   ├── Services/
│   │   ├── RAG/
│   │   │   ├── EmbeddingService.php          # Calls OpenAI to generate vectors
│   │   │   ├── RetrievalService.php          # pgvector similarity search
│   │   │   └── GenerationService.php         # Assembles prompt + calls GPT-4o
│   │   ├── Document/
│   │   │   ├── DocumentProcessorService.php  # Orchestrates chunk → embed pipeline
│   │   │   └── PdfParserService.php          # Extracts raw text from PDFs
│   │   └── AI/
│   │       └── PrismOrchestrator.php         # Multi-step agent logic (Prism PHP)
│   ├── Jobs/
│   │   ├── ProcessDocumentJob.php            # Async: parse + chunk document
│   │   └── GenerateEmbeddingJob.php          # Async: embed chunks via OpenAI
│   ├── Exceptions/
│   │   └── RAGException.php
│   └── Livewire/
│       ├── Chat/
│       │   └── ChatInterface.php             # Full-page chat component (Livewire v4)
│       └── Admin/
│           └── KnowledgeBase.php             # Full-page upload/status component
├── config/
│   ├── rag.php                               # Chunk size, overlap, model settings
│   └── openai.php                            # OpenAI API config
├── database/
│   └── migrations/
│       ├── xxxx_create_documents_table.php
│       ├── xxxx_create_document_chunks_table.php
│       └── xxxx_create_conversations_table.php
├── docker/
│   ├── php/Dockerfile
│   └── nginx/default.conf
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php                     # Root layout (required by Livewire v4)
│   └── livewire/
│       ├── chat/
│       │   └── chat-interface.blade.php      # Template for ChatInterface component
│       └── admin/
│           └── knowledge-base.blade.php      # Template for KnowledgeBase component
├── routes/
│   ├── api.php                               # REST API routes
│   └── web.php                               # Blade view routes
├── tests/
│   ├── Unit/Services/
│   │   ├── EmbeddingServiceTest.php
│   │   └── RetrievalServiceTest.php
│   └── Feature/
│       ├── Chat/SendMessageTest.php
│       └── Document/UploadDocumentTest.php
├── .env.example
├── docker-compose.yml
├── README.md
└── LICENSE
```

---

## 🚀 Quick Start (Local Dev)

### Prerequisites
- Docker & Docker Compose
- PHP 8.2+ & Composer
- Node.js & npm

### 1. Clone & Install
```bash
git clone https://github.com/your-username/rag-chatbot.git
cd rag-chatbot
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configure Environment
Edit `.env` and set your OpenAI API key and database credentials:
```
OPENAI_API_KEY=sk-...
DB_CONNECTION=pgsql
DB_DATABASE=rag_chatbot
```

### 3. Start with Docker
```bash
docker-compose up -d
php artisan migrate
php artisan db:seed
```

### 4. Start Queue Worker
```bash
php artisan queue:work
```

### 5. Visit the App
- **Chat UI**: http://localhost:8000/chat
- **Admin / Knowledge Base**: http://localhost:8000/admin/knowledge-base

---

## 🔑 Key Environment Variables

| Variable              | Description                            |
|-----------------------|----------------------------------------|
| `OPENAI_API_KEY`      | Your OpenAI secret key                 |
| `OPENAI_MODEL`        | Model name (default: `gpt-4o`)         |
| `OPENAI_EMBED_MODEL`  | Embedding model (`text-embedding-3-small`) |
| `RAG_CHUNK_SIZE`      | Token size per chunk (default: `512`)  |
| `RAG_CHUNK_OVERLAP`   | Overlap between chunks (default: `50`) |
| `RAG_TOP_K`           | Number of chunks to retrieve (default: `5`) |

---

## 🧪 Running Tests

```bash
php artisan test
php artisan test --filter EmbeddingServiceTest
```

---

## 📦 Key Composer Packages

```bash
composer require openai-php/client          # OpenAI API client
composer require echolabsdev/prism-php      # LLM orchestration / agents
composer require smalot/pdfparser           # PDF text extraction
composer require pgvector/pgvector          # pgvector PHP helpers
```

---

## 📄 License

MIT License. See [LICENSE](LICENSE) for details.
