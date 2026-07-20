<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Fills system reference numbers (order_no, invoice_no, codes, line_no) on create
 * when the caller didn't supply one — so every entry point (admin forms, POS,
 * storefront, seeders, tinker) numbers records identically and a human never has
 * to invent one.
 *
 * A model opts in by declaring generatedCodes(): a map of field => generator.
 * An explicitly-supplied value always wins, so callers can still pass their own
 * (e.g. POS/storefront pass a channel prefix).
 */
trait HasGeneratedCode
{
    public static function bootHasGeneratedCode(): void
    {
        static::creating(function (Model $model): void {
            foreach ($model->generatedCodes() as $field => $make) {
                if (blank($model->{$field})) {
                    $model->{$field} = $make($model);
                }
            }
        });
    }

    /**
     * Field => generator. Overridden per model.
     *
     * @return array<string, callable(static): (string|int)>
     */
    protected function generatedCodes(): array
    {
        return [];
    }

    /**
     * Next free sequential value for $field: PREFIX0001, PREFIX0002 …
     * Counts the existing prefix group, then walks forward past any gap-collisions.
     */
    public static function sequentialCode(string $field, string $prefix, int $pad = 4): string
    {
        $seq = static::query()->where($field, 'like', $prefix . '%')->count() + 1;

        do {
            $candidate = $prefix . str_pad((string) $seq++, $pad, '0', STR_PAD_LEFT);
        } while (static::query()->where($field, $candidate)->exists());

        return $candidate;
    }

    /** PREFIX + random suffix — for codes that shouldn't be guessable or sequential. */
    public static function randomCode(string $field, string $prefix, int $length = 6): string
    {
        do {
            $candidate = $prefix . strtoupper(Str::random($length));
        } while (static::query()->where($field, $candidate)->exists());

        return $candidate;
    }

    /** Next line number within a parent (1, 2, 3 …). */
    public static function nextLineNo(string $parentKey, int|string|null $parentId): string
    {
        return (string) (static::query()->where($parentKey, $parentId)->count() + 1);
    }
}
