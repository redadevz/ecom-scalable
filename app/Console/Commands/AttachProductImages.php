<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

/**
 * Attach image files from a folder to items (Spatie "images" collection),
 * cycling through the available photos. Handy for demoing the POS/catalog.
 *
 *   php artisan pos:attach-images ~/Desktop/product-pics --fresh
 */
class AttachProductImages extends Command
{
    protected $signature = 'pos:attach-images {dir : Folder containing image files} {--fresh : Remove existing item images first}';

    protected $description = 'Attach images from a folder to items (round-robin), for the POS/catalog';

    public function handle(): int
    {
        $dir = rtrim((string) $this->argument('dir'), '/');
        $files = collect(glob("{$dir}/*.{png,jpg,jpeg,webp,PNG,JPG,JPEG,WEBP}", GLOB_BRACE))->values();

        if ($files->isEmpty()) {
            $this->error("No image files found in: {$dir}");

            return self::FAILURE;
        }

        $items = Item::where('is_active', true)->orderBy('id')->get();
        if ($items->isEmpty()) {
            $this->error('No active items found.');

            return self::FAILURE;
        }

        $this->info("Found {$files->count()} image(s) → attaching to {$items->count()} item(s)…");

        foreach ($items as $i => $item) {
            if ($this->option('fresh')) {
                $item->clearMediaCollection('images');
            }

            $path = $files[$i % $files->count()];
            $item->addMedia($path)->preservingOriginal()->toMediaCollection('images');

            $this->line('  ' . str_pad($item->name, 26) . ' ← ' . basename($path));
        }

        $this->info('Done. Hard-refresh the POS to see the thumbnails.');

        return self::SUCCESS;
    }
}
