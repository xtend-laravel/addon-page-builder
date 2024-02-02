<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Lunar\Base\Traits\HasTranslations;
use Lunar\Base\Traits\HasUrls;
use XtendLunar\Addons\PageBuilder\Concerns\WithSlug;

class BlogCategory extends Model
{
    use HasTranslations;
    use WithSlug;

    protected $table = 'xtend_builder_blog_categories';

    protected $guarded = [];

    protected $casts = [
        'name' => AsCollection::class,
        'description' => AsCollection::class,
        'is_visible' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id', 'id');
    }

}
