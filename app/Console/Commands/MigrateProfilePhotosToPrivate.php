<?php

namespace App\Console\Commands;

use App\Models\Photo;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

#[Signature('app:migrate-profile-photos-to-private')]
#[Description('Move locally stored profile photos from public storage to private storage')]
class MigrateProfilePhotosToPrivate extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $migrated = 0;
        $skipped = 0;

        Photo::query()->select(['id', 'file_path'])->chunkById(100, function (Collection $photos) use (&$migrated, &$skipped): void {
            foreach ($photos as $photo) {
                if (filter_var($photo->file_path, FILTER_VALIDATE_URL)) {
                    $skipped++;

                    continue;
                }

                if (Storage::disk('private')->exists($photo->file_path)) {
                    if (Storage::disk('public')->exists($photo->file_path)) {
                        Storage::disk('public')->delete($photo->file_path);
                    }
                    $migrated++;

                    continue;
                }

                if (! Storage::disk('public')->exists($photo->file_path)) {
                    $skipped++;

                    continue;
                }

                Storage::disk('private')->put($photo->file_path, Storage::disk('public')->get($photo->file_path));
                Storage::disk('public')->delete($photo->file_path);
                $migrated++;
            }
        });

        $this->info("Migrated {$migrated} profile photo(s); skipped {$skipped} external or missing photo(s).");

        return self::SUCCESS;
    }
}
