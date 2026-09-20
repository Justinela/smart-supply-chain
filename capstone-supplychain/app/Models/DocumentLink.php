<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'linkable_type',
        'linkable_id',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function linkable()
    {
        return $this->morphTo();
    }
}
