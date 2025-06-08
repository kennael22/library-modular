<?php

namespace Modules\MemberType\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Support\Models\BaseModel;
use Modules\Support\Traits\Searchable;
use Modules\Support\Traits\ActivityLog;

class MemberType extends BaseModel
{
    use ActivityLog, Searchable, SoftDeletes;

    protected $table = 'member_types';
}
