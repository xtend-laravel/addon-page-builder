<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Lunar\Base\Traits\HasTranslations;

class CmsPage extends Pivot
{
    use HasTranslations;

    protected $table = 'xtend_builder_cms_page';

    protected $casts = [
        'heading' => AsCollection::class,
        'content' => AsCollection::class,
    ];

    protected $fillable = [
        'widget_slot_id',
        'image_upload',
        'heading',
        'content',
    ];

    public function widgetSlot(): BelongsTo
    {
        return $this->belongsTo(WidgetSlot::class, 'widget_slot_id');
    }
}
