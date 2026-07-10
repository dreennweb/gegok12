<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Document;

class DocumentPolicy
{
    /**
     * Determine if user can view document
     */
    public function view(User $user, Document $document): bool
    {
        // Applicant can view their documents
        if ($user->id === $document->business->user_id) {
            return true;
        }

        // Department staff can view documents for their department
        if ($document->department_id && $user->departments()->pluck('id')->contains($document->department_id)) {
            return true;
        }

        // Superadmin can view all
        return $user->hasRole('superadmin');
    }

    /**
     * Determine if user can download document
     */
    public function download(User $user, Document $document): bool
    {
        return $this->view($user, $document);
    }

    /**
     * Determine if user can upload document
     */
    public function upload(User $user, Document $document): bool
    {
        // Only applicant can upload
        return $user->id === $document->business->user_id;
    }
}
