<?php

use App\Livewire\Admin\AdminLogin;
use App\Livewire\Admin\KnowledgeBaseAdmin;
use App\Livewire\Admin\KnowledgeBaseCreate;
use App\Livewire\Admin\KnowledgeBaseShow;
use App\Livewire\Chat\ChatIndex;
use App\Livewire\Chat\ChatInterface;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('chat.index'));

// Client routes
Route::get('/chat', ChatIndex::class)->name('chat.index');
Route::get('/chat/{knowledgeBase}', ChatInterface::class)->name('chat');

// Admin login — public, must be before middleware group
Route::get('/admin/login', AdminLogin::class)->name('admin.login');

// Admin routes — protected
Route::middleware(EnsureIsAdmin::class)->group(function () {
    Route::get('/admin/knowledge-base', KnowledgeBaseAdmin::class)
        ->name('knowledge-bases.index');
    Route::get('/admin/knowledge-base/create', KnowledgeBaseCreate::class)
        ->name('knowledge-bases.create');
    Route::get('/admin/knowledge-base/{knowledgeBase}', KnowledgeBaseShow::class)
        ->name('knowledge-bases.show');
});