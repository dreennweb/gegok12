<?php

namespace App\Http\Controllers\API;

use App\Models\Application;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Download document with authorization check
     */
    public function download(Document $document)
    {
        $this->authorize('download', $document);

        return Storage::disk('secure_documents')
            ->download($document->file_path, $document->file_name);
    }

    /**
     * Get signed URL for document
     */
    public function getSignedUrl(Document $document)
    {
        $this->authorize('view', $document);

        return response()->json([
            'url' => $document->signed_url,
            'expires_at' => now()->addHours(24),
        ]);
    }
}
