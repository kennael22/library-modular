<?php

namespace Modules\Book\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Author\Models\Author;
use Modules\Support\Models\BaseModel;
use Modules\Support\Traits\Searchable;
use Modules\Support\Traits\ActivityLog;
use Illuminate\Support\Facades\Storage;
class Book extends BaseModel
{
    use ActivityLog, Searchable, SoftDeletes;

    protected $table = 'books';

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    // boot method
    public static function boot()
    {
        parent::boot();

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
}
