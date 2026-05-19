<?php

use App\Livewire\Admin\KnowledgeBaseAdmin;
use App\Livewire\Admin\KnowledgeBaseCreate;
use App\Livewire\Admin\KnowledgeBaseShow;
use App\Livewire\Chat;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('knowledge-bases.index'));

// Static routes first
Route::get('/admin/knowledge-base', KnowledgeBaseAdmin::class)
    ->name('knowledge-bases.index');

Route::get('/admin/knowledge-base/create', KnowledgeBaseCreate::class)
    ->name('knowledge-bases.create');

// Dynamic route last
Route::get('/admin/knowledge-base/{knowledgeBase}', KnowledgeBaseShow::class)
    ->name('knowledge-bases.show');

// Livewire chat route
Route::get('/chat', Chat::class)
    ->name('chat');