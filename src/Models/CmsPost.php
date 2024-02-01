<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPost extends Model
{
    protected $table = 'xtend_builder_cms_posts';

    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CmsCategory::class, 'category_id', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(\App\Models\LunarStaff::class, 'lunar_staff_id', 'id');
    }
}