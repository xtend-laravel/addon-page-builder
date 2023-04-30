<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Lunar\Models\Language;

class WidgetSlot extends Model
{
    protected $table = 'xtend_builder_widget_slots';

    protected $fillable = [
        'language_id',
        'name',
        'description',
        'identifier',
        'enabled',
        'params',
    ];

    protected $casts = [
        'params' => 'array',
    ];

    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Widget::class,
            table: 'xtend_builder_widget_slot_item',
            foreignPivotKey: 'widget_slot_id',
            relatedPivotKey: 'widget_id',
        );
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(
            related: Language::class,
            foreignKey: 'language_id',
            ownerKey: 'id',
        );
    }
}
