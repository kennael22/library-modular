<?php

namespace Modules\Author\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Book\Models\Book;
use Modules\Support\Models\BaseModel;
use Modules\Support\Traits\Searchable;
use Modules\Support\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends BaseModel
{
    use ActivityLog, Searchable, SoftDeletes, HasFactory;

    protected $table = 'authors';

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Author\Database\Factories\AuthorFactory::new();
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
