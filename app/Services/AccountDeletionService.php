<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AccountDeletionService
{
    public function delete(User $user, string $reason): void
    {
        DB::transaction(function () use ($user, $reason): void {
            $photos = $user->photos()->get(['photos.id', 'photos.file_path']);
            $verificationPaths = $user->verificationRequests()
                ->get(['document_path', 'selfie_path'])
                ->flatMap(fn (VerificationRequest $verificationRequest): array => array_filter([$verificationRequest->document_path, $verificationRequest->selfie_path]));

            AuditLog::record($user->id, 'account_deleted', 'User', $user->id, ['reason' => $reason]);

            foreach ($photos->pluck('file_path')->merge($verificationPaths)->unique() as $path) {
                Storage::disk('private')->delete($path);
                Storage::disk('public')->delete($path);
            }

            foreach ($photos as $photo) {
                foreach (['private', 'public'] as $disk) {
                    Storage::disk($disk)->delete('blurred-photos/blurred_'.$photo->id.'.jpg');
                }
            }

            $user->tokens()->delete();
            $user->forceDelete();
        });
    }
}
