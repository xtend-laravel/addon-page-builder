<?php

namespace Xtend\Extensions\Lunar\Core\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;

class WidgetSlotItem extends Pivot
{
    protected $table = 'xtend_builder_widget_slot_item';

    // @todo Solve the issue later with the data column can not be casts to array using Pivot
    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = [
        'widget_id',
        'widget_slot_id',
        'slot_col_start',
        'slot_row_start',
        'slot_cols',
        'slot_rows',
        'position',
        'data',
    ];

    public function widgetSlot(): BelongsTo
    {
        return $this->belongsTo(WidgetSlot::class, 'widget_slot_id');
    }
}
