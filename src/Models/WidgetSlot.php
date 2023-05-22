<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WidgetSlot extends Model
{
    protected $table = 'xtend_builder_widget_slots';

    protected $fillable = [
        'name',
        'description',
        'identifier',
        'enabled',
        'params',
    ];

    protected $casts = [
        'params' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'widget_slot_id');
    }

    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Widget::class,
            table: 'xtend_builder_widget_slot_item',
            foreignPivotKey: 'widget_slot_id',
            relatedPivotKey: 'widget_id',
        )->withPivot([
            'slot_cols',
            'slot_rows',
            'position',
            'data',
        ])->orderByPivot('position');
    }
}
