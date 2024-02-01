<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;

class CmsCategory extends Model
{
    protected $table = 'xtend_builder_cms_categories';

    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany(CmsPost::class, 'category_id', 'id');
    }
}