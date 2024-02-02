<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Hub\Models\Staff;

class BlogPost extends Model
{
    protected $table = 'xtend_builder_cms_posts';

    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'lunar_staff_id', 'id');
    }
}
