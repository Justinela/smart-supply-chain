<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_number',
        'title',
        'document_type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size_bytes',
        'uploaded_by_user_id',
    ];

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function links()
    {
        return $this->hasMany(DocumentLink::class);
    }
}
