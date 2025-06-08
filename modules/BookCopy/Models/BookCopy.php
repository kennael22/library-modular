<?php

namespace Modules\BookCopy\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Support\Models\BaseModel;
use Modules\Support\Traits\Searchable;
use Modules\Support\Traits\ActivityLog;

class BookCopy extends BaseModel
{
    use ActivityLog, Searchable, SoftDeletes;

    protected $table = 'book_copies';
}
