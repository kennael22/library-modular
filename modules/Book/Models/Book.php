<?php

namespace Modules\Book\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Author\Models\Author;
use Modules\Support\Models\BaseModel;
use Modules\Support\Traits\Searchable;
use Modules\Support\Traits\ActivityLog;
use Illuminate\Support\Facades\Storage;
use Modules\BookCopy\Models\BookCopy;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends BaseModel
{
    use ActivityLog, Searchable, SoftDeletes, HasFactory;

    protected $table = 'books';

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Book\Database\Factories\BookFactory::new();
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    // boot method
    public static function boot()
    {
        parent::boot();

        static::created(function ($book) {
            // create book_copies depends on book qty
            for ($i = 0; $i < $book->quantity; $i++) {
                $book->bookCopies()->create([
                    'book_id' => $book->id,
                    'is_available' => true
                ]);
            }
        });

        static::updated(function ($book) {
            $borrowedCount = $book->bookCopies()->where('is_available', false)->count();
            $availableCount = $book->bookCopies()->where('is_available', true)->count();
            $totalCopies = $borrowedCount + $availableCount;
            $desiredQty = $book->quantity;

            if ($desiredQty > $totalCopies) {
                // Add more available copies
                $toAdd = $desiredQty - $totalCopies;
                for ($i = 0; $i < $toAdd; $i++) {
                    $book->bookCopies()->create([
                        'is_available' => true
                    ]);
                }
            } elseif ($desiredQty < $totalCopies) {
                $toRemove = $totalCopies - $desiredQty;

                // Only delete available copies — never touch borrowed ones
                $book->bookCopies()
                    ->where('is_available', true)
                    ->latest() // delete newest copies first
                    ->take($toRemove)
                    ->delete();
            }
        });

        static::updating(function ($book) {
            if ($book->isDirty('cover_image')) {
                $oldImage = $book->getOriginal('cover_image');
                $oldFilePath = storage_path('app/public/book/' . $oldImage);
                info($oldImage);
                if ($oldImage && file_exists($oldFilePath)) {
                    info('if');
                    unlink($oldFilePath);
                }
            }
        });
    }

    public function bookCopies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BookCopy::class);
    }
}
