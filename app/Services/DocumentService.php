<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Business;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService
{
    protected string $disk = 'secure_documents';
    protected array $allowedExtensions = ['pdf', 'doc', 'docx', 'xlsx', 'jpg', 'jpeg', 'png'];
    protected int $maxFileSize = 10240; // KB

    /**
     * Upload and store document
     */
    public function uploadDocument(
        UploadedFile $file,
        Business $business,
        string $documentType,
        ?int $departmentId = null
    ): Document {
        $this->validateFile($file);

        $path = $this->storeFile($file, $business->id);

        return Document::create([
            'business_id' => $business->id,
            'department_id' => $departmentId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'document_type' => $documentType,
            'status' => 'pending',
            'uploaded_by' => auth()->user()->name ?? 'System',
        ]);
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $this->allowedExtensions)) {
            throw new \InvalidArgumentException(
                "File type '{$extension}' is not allowed. Allowed types: " . implode(', ', $this->allowedExtensions)
            );
        }

        if ($file->getSize() / 1024 > $this->maxFileSize) {
            throw new \InvalidArgumentException(
                "File size exceeds maximum allowed size of {$this->maxFileSize}KB"
            );
        }
    }

    /**
     * Store file securely
     */
    protected function storeFile(UploadedFile $file, int $businessId): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = "businesses/{$businessId}/" . date('Y/m/d') . "/" . $filename;

        Storage::disk($this->disk)->putFileAs(
            "businesses/{$businessId}/" . date('Y/m/d'),
            $file,
            $filename,
            'private'
        );

        return $path;
    }

    /**
     * Get signed URL for document
     */
    public function getSignedUrl(Document $document, int $expirationMinutes = 1440): ?string
    {
        if (!$document->file_path) {
            return null;
        }

        return Storage::disk($this->disk)->temporaryUrl(
            $document->file_path,
            now()->addMinutes($expirationMinutes)
        );
    }

    /**
     * Delete document
     */
    public function deleteDocument(Document $document): bool
    {
        Storage::disk($this->disk)->delete($document->file_path);
        return $document->delete();
    }
}
