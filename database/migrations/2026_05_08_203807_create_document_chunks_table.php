<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // This enables the extension in your new database
        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');

        Schema::create('document_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knowledge_base_id')->constrained()->cascadeOnDelete();
            $table->text('content');

            // 1536 is standard for OpenAI embeddings
            $table->vector('embedding', dimensions: 1536);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_chunks');
    }
};
