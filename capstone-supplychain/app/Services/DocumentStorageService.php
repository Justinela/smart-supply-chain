<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentLink;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentStorageService
{
    protected array $allowedMimes = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    protected array $forbiddenExtensions = [
        'php', 'phtml', 'php3', 'php4', 'php5', 'phps', 'exe', 'sh', 'bat', 'cmd', 'js', 'vbs', 'py', 'pl', 'cgi', 'dll', 'so'
    ];

    protected int $maxSizeBytes = 10485760; // 10MB limit

    public function uploadDocument(UploadedFile $file, string $documentType, string $title, int $uploadedByUserId, ?Model $linkableModel = null): Document
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, $this->forbiddenExtensions) || !in_array($file->getMimeType(), $this->allowedMimes)) {
            throw new Exception("Security violation: Invalid or dangerous file extension/MIME type. Allowed formats: PDF, PNG, JPG, DOCX, XLSX.");
        }

        if ($file->getSize() > $this->maxSizeBytes) {
            throw new Exception("File size exceeds maximum threshold of 10MB.");
        }

        $docNumber = 'DOC-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $originalFilename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $storedFilename = $docNumber . '.' . $extension;

        // Store outside public web root for security
        $path = $file->storeAs('secure_documents/' . strtolower($documentType), $storedFilename);

        $document = Document::create([
            'document_number' => $docNumber,
            'title' => $title,
            'document_type' => $documentType,
            'file_path' => $path,
            'file_name' => $originalFilename,
            'mime_type' => $file->getMimeType(),
            'file_size_bytes' => $file->getSize(),
            'uploaded_by_user_id' => $uploadedByUserId,
        ]);

        if ($linkableModel) {
            DocumentLink::create([
                'document_id' => $document->id,
                'linkable_type' => get_class($linkableModel),
                'linkable_id' => $linkableModel->id,
            ]);
        }

        return $document;
    }
}
