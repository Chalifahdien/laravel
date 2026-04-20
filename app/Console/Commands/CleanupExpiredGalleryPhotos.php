<?php

namespace App\Console\Commands;

use App\Models\PhotoSession;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CleanupExpiredGalleryPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gallery:cleanup-expired
                            {--hours=120 : Jumlah jam setelah dibuat sebelum foto dihapus (default 3 hari)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus foto galeri yang sudah lebih dari 3 hari sejak dibuat';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $cutoff = now()->subHours($hours);

        $sessions = PhotoSession::with(['photos', 'finalImage'])
            ->where('status', 'DONE')
            ->whereNotNull('finished_at')
            ->where('finished_at', '<', $cutoff)
            ->get();

        if ($sessions->isEmpty()) {
            $this->info("Tidak ada foto yang perlu dihapus (lebih dari {$hours} jam).");

            return self::SUCCESS;
        }

        $deletedCount = 0;
        $this->info("Menemukan {$sessions->count()} session yang akan dihapus...");

        foreach ($sessions as $session) {
            /** @var \App\Models\PhotoSession $session */
            DB::beginTransaction();

            try {
                // Hapus file SessionPhoto
                foreach ($session->photos as $photo) {
                    if ($photo->photo_path && Storage::disk('public')->exists($photo->photo_path)) {
                        Storage::disk('public')->delete($photo->photo_path);
                        $deletedCount++;
                    }
                }

                // Hapus file FinalImage
                if ($session->finalImage && $session->finalImage->image_path) {
                    if (Storage::disk('public')->exists($session->finalImage->image_path)) {
                        Storage::disk('public')->delete($session->finalImage->image_path);
                        $deletedCount++;
                    }
                }

                // Hapus folder session jika ada
                $sessionFolder = "sessions/{$session->id}";
                if (Storage::disk('public')->exists($sessionFolder)) {
                    Storage::disk('public')->deleteDirectory($sessionFolder);
                }

                // Hapus record (cascade akan hapus SessionPhoto, FinalImage, Download)
                $session->delete();

                DB::commit();
                $this->line("  ✓ Session #{$session->id} dihapus.");
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error("  ✗ Error pada session #{$session->id}: {$e->getMessage()}");
            }
        }

        $this->info("Selesai. {$sessions->count()} session dan {$deletedCount} file foto dihapus.");

        return self::SUCCESS;
    }
}
