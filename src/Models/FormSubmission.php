<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FormSubmission extends Pivot
{
    protected $table = 'xtend_builder_form_submission';

    protected $casts = [
        'payload' => 'array',
    ];

    protected $guarded = [];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
