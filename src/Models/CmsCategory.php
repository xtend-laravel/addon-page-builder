<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Lunar\Base\Traits\HasTranslations;
use Lunar\Base\Traits\HasUrls;

class CmsCategory extends Model
{
    use HasTranslations;

    protected $table = 'xtend_builder_cms_categories';

    protected $guarded = [];

    protected $casts = [
        'name' => AsCollection::class,
        'description' => AsCollection::class,
        'is_visible' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(CmsPost::class, 'category_id', 'id');
    }

    protected static function booted()
    {
        static::creating(function (CmsCategory $category) {
            $category->slug = $category->makeSlug();
        });
    }

    public function makeSlug(): ?string
    {
        $slug = Str::slug($this->name['en']);

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug .= '-' . strtolower(Str::random(5));
        }

        return $slug;
    }
}