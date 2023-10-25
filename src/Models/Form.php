<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    protected $table = 'xtend_builder_forms';

    protected $casts = [
        'fields' => 'array',
        'validation' => 'array',
    ];

    protected $fillable = [
        'name',
        'fields',
        'validation',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }
}
