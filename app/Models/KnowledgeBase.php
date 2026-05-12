<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DocumentChunk;

class KnowledgeBase extends Model
{
    protected $fillable = [
        'title',
        'original_filename',
        'file_path',
        'status',
    ];

    public function isGenerating(): bool
    {
        return $this->status === 'generating';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function chunks(): HasMany
    {
        return $this->hasMany(DocumentChunk::class);
    }
}
