<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function submissions(): BelongsToMany
    {
        return $this->belongsToMany(
            related: FormSubmission::class,
            table: 'xtend_builder_form_submission',
            foreignPivotKey: 'form_id',
            relatedPivotKey: 'form_submission_id',
        )->withPivot([
            'payload',
        ])->withTimestamps();
    }
}
