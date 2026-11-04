<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

// Daily cleanup of old notifications
Schedule::call(function () {
    // Delete read notifications older than 30 days
    DB::table('notifications')
        ->whereNotNull('read_at')
        ->where('created_at', '<', now()->subDays(30))
        ->delete();

    // Delete unread notifications older than 60 days
    DB::table('notifications')
        ->where('created_at', '<', now()->subDays(60))
        ->delete();
})->daily()->name('cleanup:notifications')->onOneServer();

// Remove mobile API tokens that expired more than a day ago
Schedule::command('sanctum:prune-expired --hours=24')->daily();

// One-off: move registration documents uploaded before they became private
// from the public disk to the private "local" disk. Copies first; pass
// --delete-public only after checking the admin dashboard still opens them.
Artisan::command('resqnet:secure-registration-documents {--delete-public}', function () {
    $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::where('collection_name', 'registration_documents')
        ->where('disk', 'public')
        ->get();

    foreach ($media as $item) {
        $relative = $item->id . '/' . $item->file_name;
        $public = \Illuminate\Support\Facades\Storage::disk('public');

        if (! $public->exists($relative)) {
            $this->warn("Missing file for media #{$item->id}, skipped.");
            continue;
        }

        \Illuminate\Support\Facades\Storage::disk('local')->put($relative, $public->get($relative));
        $item->update(['disk' => 'local', 'conversions_disk' => 'local']);

        if ($this->option('delete-public')) {
            $public->deleteDirectory((string) $item->id);
        }

        $this->info("Secured media #{$item->id}");
    }

    $this->info("Done: {$media->count()} document(s) processed.");
})->purpose('Move organization registration documents to private storage');
