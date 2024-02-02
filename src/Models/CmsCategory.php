<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CmsCategory extends Model
{
    protected $table = 'xtend_builder_cms_categories';

    protected $guarded = [];

    protected $casts = [
        'name' => 'json',
        'description' => 'json',
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