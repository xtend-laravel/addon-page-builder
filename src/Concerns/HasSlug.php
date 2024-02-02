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

    public function makeSlug(): ?string
    {
        $slug = Str::slug($this->name['en']); // haha

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug .= '-' . strtolower(Str::random(5));
        }

        return $slug;
    }
}