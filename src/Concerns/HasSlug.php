<?php

namespace XtendLunar\Addons\PageBuilder\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{

    public static function bootWithSlug()
    {
        static::creating(function (Model $model) {
            $model->slug = $model->makeSlug();
        });
    }

    public function makeSlugFrom()
    {
        return $this->name['en'];
    }

    public function makeSlug(): ?string
    {
        $slug = Str::slug($this->makeSlugFrom()); // haha

        if (blank($slug)) {
            return null;
        }

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug .= '-' . strtolower(Str::random(5));
        }

        return $slug;
    }
}