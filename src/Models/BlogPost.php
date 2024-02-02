<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Base\Traits\HasTranslations;
use Lunar\Hub\Models\Staff;
use XtendLunar\Addons\PageBuilder\Concerns\HasSlug;

class BlogPost extends Model
{
    use HasTranslations;

    protected $table = 'xtend_builder_blog_posts';

    protected $guarded = [];

    protected $casts = [
        'title' => AsCollection::class,
        'excerpt' => AsCollection::class,
        'content' => AsCollection::class,
        'is_visible' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'lunar_staff_id', 'id');
    }

    public function makeSlugFrom()
    {
        return $this->title['en'];
    }
}
